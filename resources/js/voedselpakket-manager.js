/**
 * VoedselpakketManager
 *
 * Beheert het formulier voor voedselpakketten.
 * Zorgt voor:
 * - Producten ophalen per klant.
 * - Rijen toevoegen/verwijderen.
 * - Validatie (voorraad, duplicaten, server-fouten).
 * - Styling van errors (rood vs normaal).
 */
export class VoedselpakketManager {

    // CSS classes voor validatie states (Tailwind)
    static STYLES = {
        normal: "border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500",
        error: "border-red-500 ring-1 ring-red-500 bg-red-50 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 dark:bg-red-900/20 dark:border-red-500",
        // Deze class verwijderen we expliciet bij errors om conflicten te voorkomen
        focusIndigo: "focus:border-indigo-500 focus:ring-indigo-500",
        disabledInfo: "bg-gray-100 text-gray-400 cursor-not-allowed",
        disabledBtn: "opacity-50 cursor-not-allowed"
    };

    /**
     * @param {Object} config - Selectors en instellingen.
     */
    constructor(config) {
        this.config = config;
        this.errorIndices = config.errorIndices || [];

        // Cache DOM elementen
        this.dom = {
            container: $(`#${config.containerId}`),
            addBtn: $(`#${config.addBtnId}`),
            klantSelect: $(`#${config.klantSelectId}`),
            submitBtn: $(config.submitBtnSelector),
            toastContainer: $(`#${config.toastContainerId || 'toast-container'}`)
        };

        // Applicatie state
        this.state = {
            products: [],      // Beschikbare producten
            rowCount: 0,       // Unique ID counter
            initialData: '[]'  // Voor 'dirty check' (wijzigingen detectie)
        };

        this.init();
    }

    /**
     * Start event listeners.
     */
    init() {
        const { addBtn, klantSelect, container } = this.dom;

        // Knoppen en dropdowns
        addBtn.on('click', () => this.addProductRow());
        klantSelect.on('change', (e) => this.fetchProductsForClient($(e.target).val()));

        // Event delegation voor dynamische rijen:

        // 1. Als product verandert -> Update max en valideer
        container.on('change', 'select.product-select', (e) => {
             const $target = $(e.target);
             this.clearServerErrors($target); // Oude server-fout weghalen
             this.handleProductSelection($target);
             this.validateForm();
        });

        // 2. Als aantal verandert -> Valideer input
        container.on('input change blur', 'input.quantity-input', (e) => {
            const $target = $(e.target);
            this.clearServerErrors($target);
            this.handleQuantityChange(e, $target);
            this.validateForm();
        });

        // 3. Rij verwijderen
        container.on('click', '.remove-row-btn', (e) => {
            $(e.target).closest('.product-row').remove();
            this.validateForm();
        });

        // Initïele status check
        this.checkInitialState();
    }

    /**
     * Controleert of er al een klant geselecteerd is bij laden pagia.
     */
    checkInitialState() {
        const initialClient = this.dom.klantSelect.val();
        if(initialClient) {
            this.fetchProductsForClient(initialClient, true);
        } else {
            this.toggleInterface(false);
        }
    }

    /**
     * Verwijdert de hardnekkige server-error class zodat interactie weer normaal is.
     */
    clearServerErrors($el) {
        $el.removeClass('server-side-error');
        $el.closest('.product-row').find('input').removeClass('server-side-error');
    }

    /**
     * Zet interactie aan/uit (voorkomt toevoegen zonder klant).
     */
    toggleInterface(enabled) {
        const { container, addBtn, submitBtn } = this.dom;
        const els = container.find('input, select, button.remove-row-btn');

        els.prop('disabled', !enabled);
        addBtn.prop('disabled', !enabled).toggleClass(VoedselpakketManager.STYLES.disabledBtn, !enabled);

        // Submit knop wordt apart geregeld door validateForm, maar hier vast een reset
        if(!enabled) submitBtn.prop('disabled', true).addClass(VoedselpakketManager.STYLES.disabledBtn);
    }

    /**
     * Haalt producten op van de server.
     */
    fetchProductsForClient(clientId, isInitialLoad = false) {
        if (!clientId) {
            this.resetForm();
            return Promise.resolve();
        }

        const endpoint = this.config.productsEndpoint || '/voedselpakketten/producten/';

        return axios.get(`${endpoint}${clientId}`)
            .then(res => {
                this.state.products = res.data;
                this.toggleInterface(true);
                this.refreshExistingRows(); // Update bestaande dropdowns met nieuwe data
                this.validateForm();
            })
            .catch(err => {
                console.error("Fout bij ophalen producten:", err);
                this.showToast('Fout bij ophalen gegevens.', 'error');
                this.resetForm();
            });
    }

    resetForm() {
        this.state.products = [];
        this.dom.container.empty();
        this.toggleInterface(false);
    }

    /**
     * Ververst alle openstaande rijen met de nieuwe productlijst.
     * Behoudt de geselecteerde waarde indien mogelijk.
     */
    refreshExistingRows() {
        const self = this;
        this.dom.container.find('.product-row').each(function() {
            const $row = $(this);
            const $select = $row.find('select.product-select');
            const currentVal = $select.val();

            // Onthoud naam voor fallback
            let currentText = $select.find('option:selected').text();
            currentText = currentText.replace(' (Niet toegestaan)', '').replace(/ \(\d+ beschikbaar\)/, '');

            self.renderSelectOptions($select, currentVal, { name: currentText });
        });
    }

    /**
     * Voegt een nieuwe HTML rij toe.
     */
    addProductRow(data = null, explicitIndex = null) {
        if (!this.dom.klantSelect.val() && !data) {
            this.showToast('Selecteer eerst een klant.', 'error');
            return;
        }

        // Index bepalen (unieke identifier voor form name)
        let index = explicitIndex !== null ? parseInt(explicitIndex) : ++this.state.rowCount;
        if (explicitIndex > this.state.rowCount) this.state.rowCount = index; // Sync counter

        const currentQty = data ? data.aantal : 1;

        // Check of deze rij een fout had vanaf serverload
        const isServerError = this.errorIndices.map(String).includes(String(index));
        const inputClass = isServerError
            ? `${VoedselpakketManager.STYLES.error} server-side-error`
            : VoedselpakketManager.STYLES.normal;

        const rowHtml = `
            <div class="flex gap-3 product-row mb-3 items-start">
                <div class="flex-1">
                    <select name="producten[${index}][product_id]" class="product-select w-full ${inputClass}">
                        <option value="">-- Selecteer product --</option>
                    </select>
                </div>
                <div>
                    <input type="number" name="producten[${index}][aantal]"
                           min="1" value="${currentQty}" placeholder="Aantal"
                           class="quantity-input w-24 ${inputClass}">
                </div>
                <button type="button" class="remove-row-btn text-red-600 hover:text-red-800 font-bold px-2 py-2">
                    &times;
                </button>
            </div>
        `;

        const $row = $(rowHtml);
        this.dom.container.append($row);

        // Vul de dropdown opties
        const $select = $row.find('select');
        let meta = data ? { name: data.name, stock: data.stock } : null;
        this.renderSelectOptions($select, data ? data.product_id : null, meta);

        // Direct valideren als het data bevat (bij edit/error load)
        if(data) this.validateRow($row);
    }

    /**
     * Vult de <select> met opties uit this.state.products.
     */
    renderSelectOptions($select, selectedValue, extraMeta) {
        $select.find('option:not(:first)').remove(); // Clear oude opties

        let found = false;

        this.state.products.forEach(prod => {
            const stock = prod.aantal_voorraad || prod.voorraad;
            const option = $('<option></option>')
                .attr('value', prod.id)
                .text(`${prod.product_naam || prod.naam} (${stock} beschikbaar)`)
                .data('stock', stock);

            if(selectedValue && parseInt(selectedValue) === prod.id) {
                option.prop('selected', true);
                found = true;
            }
            $select.append(option);
        });

        // Fallback: Als product niet meer bestaat in de lijst (bijv. op=op), maar wel geselecteerd was
        if (selectedValue && !found && extraMeta) {
             const option = $('<option></option>')
                    .attr('value', selectedValue)
                    .text(`${extraMeta.name || 'Onbekend'} (Niet toegestaan)`)
                    .data('stock', extraMeta.stock || 0)
                    .prop('selected', true);
            $select.append(option);
        }
    }

    handleProductSelection($select) {
        // Update input limiet en valideer
        const $row = $select.closest('.product-row');
        this.updateInputLimit($row);
        this.validateRow($row);
    }

    /**
     * Valideer input getallen (minimaal 1 en maximaal voorraad).
     */
    handleQuantityChange(event, $input) {
        let val = parseInt($input.val());

        // Blur event: Corrigeer lege of ongeldige waardes
        if (event.type === 'blur' && (!val || isNaN(val))) {
            $input.val(1);
        }

        // Typen: alleen ingrijpen als < 1
        if (!isNaN(val) && val < 1) {
            $input.val(1);
        }

        const $row = $input.closest('.product-row');
        this.validateRow($row);
    }

    /**
     * Stelt het max attribuut in op de input zodat de browser validatie helpt.
     */
    updateInputLimit($row) {
        const $select = $row.find('select.product-select');
        const $input = $row.find('input.quantity-input');

        let stock = $select.find('option:selected').data('stock');

        if (stock !== undefined && stock !== Infinity) {
            $input.attr('max', stock);
        } else {
            $input.removeAttr('max');
        }
    }

    /**
     * Checkt voorraad limiet en update styling.
     */
    validateRow($row) {
        const $select = $row.find('select.product-select');
        const $input = $row.find('input.quantity-input');
        const $inputContainer = $input.closest('div'); // Div om de input heen

        // Voorraad ophalen
        let stock = $select.find('option:selected').data('stock');
        if (stock === undefined) stock = Infinity;

        const qty = parseInt($input.val()) || 0;

        // Foutcondities:
        // 1. Voorraad overschreden
        const isStockError = (stock !== undefined && stock !== Infinity && qty > stock);
        // 2. Server had een error (blijft staan tot gebruiker iets doet)
        const isServerError = $select.hasClass('server-side-error') || $input.hasClass('server-side-error');

        const hasError = isStockError || isServerError;

        this.toggleErrorState($row, hasError);

        // Feedback bericht tonen bij stock error
        $inputContainer.find('.stock-err-msg').remove();
        if (isStockError) {
             $inputContainer.append(`<p class="stock-err-msg text-xs text-red-600 mt-1 font-bold">⚠️ Maximaal ${stock} beschikbaar.</p>`);
        }

        return hasError;
    }

    /**
     * Wisselt tussen rode error-styling en normale styling.
     */
    toggleErrorState($row, hasError) {
        const $inputs = $row.find('select, input');
        const styles = VoedselpakketManager.STYLES;

        if (hasError) {
            $inputs
                .removeClass(styles.normal)
                .removeClass(styles.focusIndigo) // Voorkom kleurconflict
                .addClass(styles.error);
        } else {
            $inputs
                .removeClass(styles.error)
                .addClass(styles.normal);
        }
    }

    /**
     * Hoofdvalidatie over het hele formulier.
     * Checkt duplicaten, niet-toegestane producten en activeert opslaan knop.
     */
    validateForm() {
        const $selects = this.dom.container.find('select.product-select');

        // 1. Welke producten zijn al gekozen? (Voor disable logic)
        const selectedIds = new Set();
        $selects.each((_, el) => {
            const val = $(el).val();
            if(val) selectedIds.add(parseInt(val));
        });

        let invalidCount = 0;
        let validProductCount = 0;

        // 2. Loop rijen na
        $selects.each((index, el) => {
            const $el = $(el);
            const currentVal = parseInt($el.val());
            const $row = $el.closest('.product-row');

            // Basis validatie (voorraad + server marker)
            const rowHasError = this.validateRow($row);

            // Extra check: Is product toegestaan voor deze klant?
            let isAllowed = true;
            if (currentVal) {
                isAllowed = this.state.products.find(p => p.id === currentVal);

                if (!isAllowed) {
                    invalidCount++;
                    // Forceer error stijl + melding
                    $el.addClass('border-red-500 text-red-900').removeClass('border-gray-300');
                    if($row.find('.error-msg-client').length === 0) {
                        $row.find('> div:first').append('<p class="error-msg-client text-xs text-red-600 mt-1 font-bold">⚠️ Niet toegestaan.</p>');
                    }
                } else {
                    $row.find('.error-msg-client').remove();
                    validProductCount++;
                }
            }

            if (rowHasError) invalidCount++;

            // Disable opties die elders gekozen zijn
            $el.find('option:not(:first)').each((_, opt) => {
                const optVal = parseInt($(opt).val());
                const isSelectedElsewhere = selectedIds.has(optVal) && optVal !== currentVal;

                $(opt).prop('disabled', isSelectedElsewhere)
                      .toggleClass(VoedselpakketManager.STYLES.disabledInfo, isSelectedElsewhere);
            });
        });

        // 3. Dirty Check & Submit knop status
        const isDirty = this.takeSnapshot() !== this.state.initialData;

        // Knop uit als: fouten, niets geldig gekozen, OF geen wijzigingen
        const shouldDisable = (invalidCount > 0) || (validProductCount === 0) || (!isDirty);

        this.dom.submitBtn
            .prop('disabled', shouldDisable)
            .toggleClass(VoedselpakketManager.STYLES.disabledBtn, shouldDisable);
    }

    /**
     * Snapshot van data voor wijzigingscontrole.
     * @returns {string} JSON string
     */
    takeSnapshot() {
        const data = [];
        this.dom.container.find('.product-row').each(function() {
            const pid = parseInt($(this).find('select').val());
            const qty = parseInt($(this).find('input').val());
            if (pid && qty) data.push({ id: pid, qty });
        });

        // Sorteer op ID voor consistente vergelijking
        return JSON.stringify(data.sort((a,b) => a.id - b.id));
    }

    captureInitialState() {
        this.state.initialData = this.takeSnapshot();
        this.validateForm();
    }

    showToast(message, type = 'error') {
        const color = type === 'error' ? 'bg-red-500' : 'bg-green-500';
        const $toast = $(`
            <div class="${color} text-white px-6 py-4 rounded shadow-lg flex items-center gap-2 mb-2 transition-all duration-300 translate-y-full opacity-0">
                <span>${message}</span>
                <button type="button" class="ml-auto font-bold opacity-75 hover:opacity-100">&times;</button>
            </div>
        `);

        $toast.find('button').on('click', () => $toast.remove());
        this.dom.toastContainer.append($toast);

        // Animatie
        setTimeout(() => $toast.removeClass('translate-y-full opacity-0'), 10);
        setTimeout(() => {
            $toast.addClass('translate-y-full opacity-0');
            setTimeout(() => $toast.remove(), 300);
        }, 3000);
    }
}
