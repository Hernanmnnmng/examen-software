
/**
 * VoedselpakketManager
 *
 * Deze klasse beheert de frontend logica voor het aanmaken en bewerken van voedselpakketten.
 * Functionaliteiten:
 * - Dynamisch toevoegen/verwijderen van productrijen.
 * - Ophalen van toegestane producten per specifiek gezin (klant).
 * - Real-time validatie van voorraad (maximaal aantal).
 * - Voorkomen van dubbele productselectie in meerdere rijen.
 * - 'Dirty checking': Opslaan knop alleen activeren als er daadwerkelijk wijzigingen zijn.
 */
export class VoedselpakketManager {

    /**
     * Constructor voor de manager.
     * Initialiseert configuratie, state en DOM elementen.
     *
     * @param {Object} config - Object met ID's en selectors voor HTML elementen.
     */
    constructor(config) {
        // Configuratie opslaan
        this.config = config;

        // Cache voor jQuery elementen (prestatie optimalisatie)
        this.elements = {
            container: $(`#${config.containerId}`),         // De div waar rijen in komen
            addBtn: $(`#${config.addBtnId}`),               // Knop 'Product toevoegen'
            klantSelect: $(`#${config.klantSelectId}`),     // Dropdown voor gezinnen
            submitBtn: $(config.submitBtnSelector),         // Opslaan knop
            toastContainer: $(`#${config.toastContainerId || 'toast-container'}`) // Container voor meldingen
        };

        // Interne state van de applicatie
        this.state = {
            products: [],      // Lijst met beschikbare producten voor het geselecteerde gezin
            rowCount: 0,       // Teller voor unieke ID's van inputvelden
            initialData: '[]'  // Snapshot van de data bij laden (voor wijzigingscontrole)
        };

        // Start de applicatie
        this.init();
    }

    /**
     * Initialiseert alle event listeners en start-logica.
     */
    init() {
        // 1. Event: Klik op 'Product toevoegen'
        this.elements.addBtn.on('click', () => this.addProductRow());

        // 2. Event: Verandering van Gezin/Klant selectie
        this.elements.klantSelect.on('change', (e) => this.fetchProductsForClient($(e.target).val()));

        // 3. Event: Validatie en logica bij wijzigen van een product-dropdown
        // We gebruiken 'event delegation' (on change op container) omdat rijen dynamisch zijn
        this.elements.container.on('change', 'select.product-select', (e) => {
             this.handleProductSelection($(e.target)); // Update max voorraad
             this.validateForm(); // Controleer formulier
        });

        // 4. Event: Validatie bij typen/veranderen van aantal
        this.elements.container.on('input change blur', 'input.quantity-input', (e) => {
            this.handleQuantityChange(e, $(e.target));
            this.validateForm();
        });

        // 5. Event: Klik op 'Verwijder' knop bij een rij
        this.elements.container.on('click', '.remove-row-btn', (e) => {
            $(e.target).closest('.product-row').remove();
            this.validateForm();
        });

        // Check bij het laden van de pagina of er al een klant geselecteerd is (bijv. bij Edit of validatie-fout return)
        const initialClient = this.elements.klantSelect.val();
        if(initialClient) {
            // Laad producten, true = 'isInitialLoad' (behoud bestaande rijen)
            this.fetchProductsForClient(initialClient, true);
        } else {
            // Blokkeer interface totdat er een klant is
            this.toggleInterface(false);
        }
    }

    /**
     * Schakelt de knoppen en inputs in of uit.
     * Wordt gebruikt om te voorkomen dat gebruikers producten toevoegen zonder klant.
     *
     * @param {boolean} enabled - True om te activeren, False om te blokkeren.
     */
    toggleInterface(enabled) {
        const { container, addBtn, submitBtn } = this.elements;
        const disabledClass = 'opacity-50 cursor-not-allowed';

        if(enabled) {
            // Activeer alles
            container.find('input, select, button.remove-row-btn').prop('disabled', false);
            addBtn.prop('disabled', false)
                .removeClass(disabledClass)
                .removeAttr('title')
                .parent().removeAttr('title');
            // Submit knop wordt apart beheerd door validateForm()
        } else {
            // Deactiveer alles
            container.find('input, select, button.remove-row-btn').prop('disabled', true);
            addBtn.prop('disabled', true)
                .addClass(disabledClass)
                .attr('title', 'Selecteer eerst een klant')
                .parent().attr('title', 'Selecteer eerst een klant');
            submitBtn.prop('disabled', true)
                .addClass(disabledClass)
                .attr('title', 'Formulier is nog niet klaar om op te slaan')
                .parent().attr('title', 'Formulier is nog niet klaar om op te slaan');
        }
    }

    /**
     * Haalt de producten op die toegestaan zijn voor een specifiek gezin.
     *
     * @param {number} clanId - ID van het gezin/klant.
     * @param {boolean} isInitialLoad - Als true, wissen we de bestaande rijen NIET (voor edit pagina).
     */
    fetchProductsForClient(clientId, isInitialLoad = false) {
        // Als geen klant geselecteerd is (leeg), reset alles
        if (!clientId) {
            this.state.products = [];
            this.elements.container.empty();
            this.toggleInterface(false);
            return Promise.resolve();
        }

        const endpoint = this.config.productsEndpoint || '/voedselpakketten/producten/';

        // AJAX request naar backend
        return axios.get(`${endpoint}${clientId}`)
            .then(res => {
                this.state.products = res.data; // Sla producten op in state
                this.toggleInterface(true);     // Activeer interface

                // BELANGRIJK: Update alle bestaande rijen met de nieuwe productenlijst!
                // Dit zorgt ervoor dat als je van klant wisselt, de dropdowns ververst worden.
                // Als een product NIET in de nieuwe lijst staat, behouden we het wel (via extraMeta)
                // zodat de gebruiker het ziet, maar validateForm() zal het als fout markeren.
                const self = this;
                this.elements.container.find('.product-row').each(function() {
                    const $row = $(this);
                    const $select = $row.find('select.product-select');
                    const currentVal = $select.val(); // Behoud huidige selectie indien mogelijk

                    // Haal de tekst van de huidige optie op om te bewaren als hij niet meer bestaat
                    let currentText = $select.find('option:selected').text();
                    // Schoon de tekst op (verwijder eventuele vorige error statussen)
                    currentText = currentText.replace(' (Niet toegestaan)', '').replace(/ \(\d+ beschikbaar\)/, '');

                    // Render opties opnieuw op basis van de NIEUWE products set
                    // We geven currentText mee als 'name' in extraMeta voor de fallback
                    self.renderSelectOptions($select, currentVal, { name: currentText, stock: 999 });
                });

                this.validateForm();            // Her-valideer (update disable states en knoppen)
            })
            .catch(err => {
                console.error("Fout bij ophalen producten:", err);
                this.showToast('Kan producten niet ophalen. Controleer uw verbinding.', 'error');
                this.state.products = [];
                this.validateForm();
            });
    }

    /**
     * Voegt een nieuwe product-rij toe aan de HTML.
     *
     * @param {Object|null} data - (Optioneel) Data om in te vullen: {product_id, aantal, name, stock}.
     */
    addProductRow(data = null) {
        // Stop als er geen klant is geselecteerd (beveiliging)
        if (!this.elements.klantSelect.val() && !data) {
            this.showToast('Selecteer eerst een klant.', 'error');
            return;
        }

        this.state.rowCount++;
        const index = this.state.rowCount; // Unieke index voor name="" attributen

        // Bepaal startwaardes (leeg of vanuit data)
        const currentQty = data ? data.aantal : 1;

        // De HTML template voor één rij
        // Gebruikt Tailwind classes voor styling
        const rowHtml = `
            <div class="flex gap-3 product-row mb-3 items-start">
                <div class="flex-1">
                    <select name="producten[${index}][product_id]" class="product-select w-full rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2">
                        <option value="">-- Selecteer product --</option>
                    </select>
                </div>
                <div>
                    <input type="number" name="producten[${index}][aantal]"
                           min="1" value="${currentQty}" placeholder="Aantal"
                           class="quantity-input w-24 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-2">
                </div>
                <div>
                    <button type="button" class="remove-row-btn px-4 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200 dark:bg-red-900 dark:text-red-200 transition-colors">
                        <span class="font-bold">&times;</span>
                    </button>
                </div>
            </div>
        `;

        // Voeg de rij toe aan de container
        this.elements.container.append(rowHtml);

        // Vul de dropdown van DEZE nieuwe rij
        const $newSelect = this.elements.container.children().last().find('select');
        this.renderSelectOptions($newSelect, data ? data.product_id : null, data);

        // Trigger validatie om de rest van de pagina bij te werken
        this.validateForm();
    }

    /**
     * Vult een <select> met opties uit this.state.products.
     *
     * @param {jQuery} $select - Het select element.
     * @param {number} selectedValue - De ID die geselecteerd moet zijn (indien aanwezig).
     * @param {Object} extraMeta - Metadata (naam, voorraad) voor als het product niet in de standaard lijst staat (bijv. oude data).
     */
    renderSelectOptions($select, selectedValue, extraMeta) {
        // Verwijder alle opties behalve de eerste ('Selecteer product')
        $select.find('option:not(:first)').remove();

        let foundInList = false;

        // Loop door alle beschikbare producten
        this.state.products.forEach(prod => {
            const stock = prod.aantal_voorraad || prod.voorraad;
            const option = $('<option></option>')
                .attr('value', prod.id)
                .text(`${prod.product_naam || prod.naam} (${stock} beschikbaar)`)
                .data('stock', stock); // Sla voorraad op in data-attribuut

            // Als dit product overeenkomt met de waarde die we willen selecteren
            if(selectedValue && parseInt(selectedValue) === prod.id) {
                option.prop('selected', true);
                foundInList = true;
            }

            $select.append(option);
        });

        // Fallback: Als het product ID wel bestaat maar niet in de lijst (bijv. uitverkocht of niet toegestaan voor huidige klant)
        // Voeg het handmatig toe zodat de gebruiker ziet wat er geselecteerd was.
        if (selectedValue && !foundInList && extraMeta) {
             const option = $('<option></option>')
                    .attr('value', selectedValue)
                    .text(`${extraMeta.name || 'Onbekend'} (Niet toegestaan)`) // Duidelijke markering
                    .data('stock', extraMeta.stock || 0)
                    .prop('selected', true);
            $select.append(option);
        }
    }

    /**
     * Logica die uitgevoerd wordt als een gebruiker een product kiest.
     * Update voornamelijk het 'max' attribuut van het aantal-veld.
     */
    handleProductSelection($select) {
        // Geen client-side restricties meer hier
    }

    /**
     * Validatie van de aantallen.
     * Zorgt dat min 1 is.
     */
    handleQuantityChange(event, $input) {
        let val = parseInt($input.val());

        // Als veld leeg is of ongeldig, zet terug naar 1 (alleen bij focus verlies 'blur')
        if (event.type === 'blur' && (!val || isNaN(val))) {
            $input.val(1);
            val = 1;
        }

        // Minimum check
        if (!isNaN(val) && val < 1) {
            $input.val(1);
            val = 1;
        }
    }

    /**
     * Hoofd validatie functie. Wordt aangeroepen na elke wijziging.
     * Taken:
     * 1. Checkt welke producten al gekozen zijn in andere rijen en schakelt deze uit ('disable').
     * 2. Controleert of alle rijen geldig zijn.
     * 3. Beheert de status van de 'Opslaan' knop (Dirty Checking).
     */
    validateForm() {
        const $selects = this.elements.container.find('select.product-select');

        // 1. Verzamel alle gekozen ID's om duplicaten te voorkomen
        const selectedIds = [];
        $selects.each(function() {
            const val = $(this).val();
            if(val) selectedIds.push(parseInt(val));
        });

        let invalidCount = 0;
        let validProductCount = 0;
        let hasAnyProducts = $selects.length > 0; // Minstens één productrij toegevoegd

        // 2. Loop door elke select om opties bij te werken
        $selects.each((index, el) => {
            const $el = $(el);
            const currentVal = parseInt($el.val());
            const $row = $el.closest('.product-row');

            // Reset visual state
            $el.removeClass('border-red-500 text-red-900').addClass('border-gray-300 dark:border-gray-700 dark:text-gray-300');
            $row.find('.error-msg-client').remove();

            // STRICT Check: Is dit product toegestaan voor de huidige lijst?
            if (currentVal) {
                // this.state.products bevat de TOEGESTANE producten voor de huidige klant
                const isAllowed = this.state.products.find(p => p.id === currentVal);

                if (!isAllowed) {
                    // NIET TOEGESTAAN!
                    invalidCount++;
                    $el.addClass('border-red-500 text-red-900').removeClass('border-gray-300 dark:border-gray-700 dark:text-gray-300');
                    $row.find('> div:first').append('<p class="error-msg-client text-xs text-red-600 mt-1 font-bold">⚠️ Niet toegestaan voor deze klant.</p>');
                } else {
                    validProductCount++;
                }
            }

            // Update 'disabled' status van opties in DEZE dropdown
            $el.find('option:not(:first)').each((i, opt) => {
                const optVal = parseInt($(opt).val());

                // Als dit product al ergens anders is gekozen, disable het
                // Behalve als het de huidige selectie van deze dropdown is
                if(selectedIds.includes(optVal) && optVal !== currentVal) {
                    $(opt).prop('disabled', true).addClass('bg-gray-100 text-gray-400');
                } else {
                    $(opt).prop('disabled', false).removeClass('bg-gray-100 text-gray-400');
                }
            });
        });

        // 3. Controleer of knop aan of uit moet

        // Heeft de gebruiker iets veranderd t.o.v. het begin?
        const isDirty = this.takeSnapshot() !== this.state.initialData;

        // Knop is enabled als:
        // - Er geen fouten zijn (invalidCount === 0)
        // - Er minstens 1 productlijn bestaat (hasAnyProducts)
        // - Er wijzigingen zijn (isDirty)
        const isDisabled = (invalidCount > 0) || (!hasAnyProducts) || (!isDirty);
        const reasons = [];
        if (invalidCount > 0) reasons.push('Niet toegestane of dubbele producten');
        if (!hasAnyProducts) reasons.push('Voeg minstens en product toe');
        if (!isDirty) reasons.push('Geen wijzigingen om op te slaan');
            const disableReason = reasons.join(' • ');
        const disabledClass = 'opacity-50 cursor-not-allowed';
        const grayClass = 'bg-gray-400 hover:bg-gray-500';
        const blueClass = 'bg-blue-600 hover:bg-blue-700';

        if(isDisabled) {
            this.elements.submitBtn.prop('disabled', true)
                .addClass(disabledClass)
                .removeClass(blueClass)
                .addClass(grayClass)
                .attr('title', disableReason || 'Formulier is nog niet klaar om op te slaan')
                .parent().attr('title', disableReason || 'Formulier is nog niet klaar om op te slaan');
        } else {
            this.elements.submitBtn.prop('disabled', false)
                .removeClass(disabledClass)
                .removeClass(grayClass)
                .addClass(blueClass)
                .attr('title', 'Pakket opslaan')
                .parent().removeAttr('title');
        }
    }

    /**
     * Maakt een JSON string van de huidige formulier data.
     * Wordt gebruikt om te vergelijken of er iets gewijzigd is ('Dirty Check').
     *
     * @returns {string} JSON representatie van [ {id: 1, qty: 5}, ... ]
     */
    takeSnapshot() {
        const data = [];
        this.elements.container.find('.product-row').each(function() {
            const $row = $(this);
            const pid = parseInt($row.find('select').val());
            const qty = parseInt($row.find('input').val());

            // Alleen complete rijen tellen mee
            if (pid && qty) {
                data.push({ id: pid, qty });
            }
        });

        // Sorteer op ID zodat volgorde van rijen niet uitmaakt voor de vergelijking
        data.sort((a,b) => a.id - b.id);

        return JSON.stringify(data);
    }

    /**
     * Sla de huidige staat op als 'beginpunt'.
     * Dit roep je aan nadat de Edit-pagina de bestaande producten heeft ingeladen.
     */
    captureInitialState() {
        this.state.initialData = this.takeSnapshot();
        this.validateForm(); // Update knop status direct
    }

    /**
     * Hulpfunctie: Toon een tijdelijke notificatie (Toast).
     *
     * @param {string} message - Het bericht.
     * @param {string} type - 'error' (rood) of 'success' (groen/waarschuwing).
     */
    showToast(message, type = 'error') {
        const color = type === 'error' ? 'bg-red-500' : 'bg-green-500';

        // Maak HTML element
        const $toast = $(`
            <div class="${color} text-white px-6 py-4 rounded shadow-lg transform transition-all duration-300 translate-y-full opacity-0 flex items-center gap-2 mb-2">
                <span>${message}</span>
                <button type="button" class="ml-4 font-bold opacity-75 hover:opacity-100">&times;</button>
            </div>
        `);

        // Klik om te sluiten
        $toast.find('button').on('click', () => $toast.remove());

        // Voeg toe aan DOM
        this.elements.toastContainer.append($toast);

        // Animatie in
        setTimeout(() => $toast.removeClass('translate-y-full opacity-0'), 10);

        // Animatie uit (na 3 seconden)
        setTimeout(() => {
            $toast.addClass('translate-y-full opacity-0');
            setTimeout(() => $toast.remove(), 300);
        }, 3000);
    }
}
