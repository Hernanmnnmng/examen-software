
export class VoedselpakketManager {
    constructor(config) {
        this.containerId = config.containerId;
        this.addBtnId = config.addBtnId;
        this.klantSelectId = config.klantSelectId;
        this.submitBtnSelector = config.submitBtnSelector; // e.g. 'button[type="submit"]'
        this.toastContainerId = config.toastContainerId || 'toast-container';
        this.productsEndpoint = config.productsEndpoint || '/voedselpakketten/producten/';

        this.productsData = [];
        this.rowCount = 0;
        this.initialState = '[]'; // JSON string of sorted products

        // Bind methods
        this.handleKlantChange = this.handleKlantChange.bind(this);
        this.addProductRow = this.addProductRow.bind(this);
        this.updateProductDropdownsAndValidate = this.updateProductDropdownsAndValidate.bind(this);
        this.showToast = this.showToast.bind(this);
        this.captureInitialState = this.captureInitialState.bind(this);
        this.getSnapshot = this.getSnapshot.bind(this);

        this.init();
    }

    init() {
        // Event Listeners
        const $container = $(`#${this.containerId}`);
        const $klantSelect = $(`#${this.klantSelectId}`);
        const $addBtn = $(`#${this.addBtnId}`);

        // Add Button
        $addBtn.on('click', this.addProductRow);

        // Klant Change
        $klantSelect.on('change', (e) => this.handleKlantChange($(e.target).val()));

        // Remove Row (Event Delegation)
        $container.on('click', '.remove-row-btn', (e) => {
            $(e.target).closest('.product-row').remove();
            this.updateProductDropdownsAndValidate();
        });

        // Product Select Change
        $container.on('change', 'select[name^="producten"]', (e) => {
            this.updateProductDropdownsAndValidate();

            // Validate stock max immediately upon selection
            const $select = $(e.target);
            const stock = $select.find(':selected').data('stock');
            const $input = $select.siblings('input[type="number"]');

            if (stock !== undefined) {
                $input.attr('max', stock);
                let val = parseInt($input.val());
                if(val > stock) {
                    $input.val(stock);
                    this.showToast(`Aantal aangepast aan maximale voorraad (${stock}).`, 'error');
                }
            }
        });

        // Quantity Input Change
        $container.on('input change blur', 'input[name^="producten"][type="number"]', (e) => {
            const $input = $(e.target);
            const $select = $input.siblings('select');
            const stock = $select.find(':selected').data('stock');
            let val = parseInt($input.val());

            if (e.type === 'blur' && (!val || isNaN(val))) {
                $input.val(1);
                val = 1;
            }

            if (!isNaN(val) && val < 1) {
                $input.val(1);
                val = 1;
            }

            if (stock !== undefined && val > stock) {
                $input.val(stock);
                this.showToast(`Er zijn maar ${stock} stuks op voorraad.`, 'error');
            }

            this.updateProductDropdownsAndValidate();
        });

        // Initial State Check
        const initialKlantId = $klantSelect.val();
        if(initialKlantId) {
            this.handleKlantChange(initialKlantId, true); // true = preserve existing rows if needed
        } else {
            this.lockInterface();
        }
    }

    lockInterface() {
        $(`#${this.containerId} input, #${this.containerId} select, #${this.containerId} button`).prop('disabled', true);
        $(`#${this.addBtnId}`).prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        $(this.submitBtnSelector).prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
    }

    unlockInterface() {
        $(`#${this.containerId} input, #${this.containerId} select, #${this.containerId} button`).prop('disabled', false);
        $(`#${this.addBtnId}`).prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        // Submit button is handled by validation
    }

    handleKlantChange(klantId, isInitialLoad = false) {
        if (!klantId) {
            this.productsData = [];
            $(`#${this.containerId}`).empty(); // Clear rows safely? Or keep? Usually clear on client change.
            if(!isInitialLoad) this.rowCount = 0;
            this.lockInterface();
            return Promise.resolve();
        }

        return axios.get(`${this.productsEndpoint}${klantId}`)
            .then(response => {
                this.productsData = response.data;
                this.unlockInterface();
                this.updateProductDropdownsAndValidate();
            })
            .catch(error => {
                console.error(error);
                this.showToast('Kon producten niet ophalen.', 'error');
                this.productsData = [];
                this.updateProductDropdownsAndValidate();
            });
    }

    addProductRow(e, existingData = null) {
        if (!$(`#${this.klantSelectId}`).val() && !existingData) {
            this.showToast('Selecteer eerst een klant.', 'error');
            return;
        }

        this.rowCount++;
        // Use a generic index, or stick to rowCount.
        // Note: For Update, we might want to use array indices or just incremental.
        // Laravel handles `producten` array fine.

        const productId = existingData ? existingData.product_id : '';
        const aantal = existingData ? existingData.aantal : 1;

        const html = `
            <div class="flex gap-3 product-row mb-3">
                <select name="producten[${this.rowCount}][product_id]" class="product-select flex-1 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Selecteer product --</option>
                </select>
                <input type="number" name="producten[${this.rowCount}][aantal]" min="1" value="${aantal}" placeholder="Aantal" class="quantity-input w-20 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="button" class="remove-row-btn px-3 py-2 bg-red-100 text-red-700 rounded hover:bg-red-200 dark:bg-red-900 dark:text-red-200">Verwijder</button>
            </div>
        `;

        const $container = $(`#${this.containerId}`);
        $container.append(html);

        const $newSelect = $container.children().last().find('select');

        // Populate options based on current productsData
        this.populateSelect($newSelect, productId, existingData);

        this.updateProductDropdownsAndValidate();
    }

    populateSelect($select, selectedValue, extraMeta = null) {
        // Clear except first
        $select.find('option:not(:first)').remove();

        let found = false;

        this.productsData.forEach(prod => {
            const stock = prod.aantal_voorraad || prod.voorraad;
            // For edit mode: if this product is the one selected, we might need to add its OLD stock to the current available stock
            // to represent the "true" max if we were to unselect it.
            // logic is tricky. For now use available stock.

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

        // Handle case where cached/existing product is NOT in the active list
        if (selectedValue && !found && extraMeta && extraMeta.name) {
             const option = $('<option></option>')
                    .attr('value', selectedValue)
                    .text(`${extraMeta.name} (Niet meer beschikbaar?)`)
                    .data('stock', extraMeta.stock || 0)
                    .prop('selected', true);
            $select.append(option);
        }
    }

    updateProductDropdownsAndValidate() {
        let invalidCount = 0;
        let validProductCount = 0;
        const $container = $(`#${this.containerId}`);
        const selects = $container.find('select[name^="producten"][name$="[product_id]"]');

        const selectedIds = [];
        selects.each(function() {
            const val = $(this).val();
            if(val) selectedIds.push(parseInt(val));
        });

        const self = this; // context safety

        selects.each(function() {
            const $select = $(this);
            const currentVal = parseInt($select.val());
            const $row = $select.closest('.product-row');

            // Cleanup error state
            $select.removeClass('border-red-500 bg-red-50 text-red-900').addClass('border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300');
            $row.find('.error-msg').remove();

            // Rebuild options with duplicate Logic
            // We want to keep the current selection, but disable other options if they are selected elsewhere.
            // Also check if currentVal is valid in productsData (Client constraint)

            // 1. Check Constraint

            // If we have productsData loaded, verify.
            // (If fetching, this runs after fetch)

            let isValidForClient = true;
            let currentText = "";
            let currentStock = 0;

            if (currentVal) {
                const productInList = self.productsData.find(p => p.id === currentVal);
                if (!productInList) {
                    isValidForClient = false;
                    // Try to retrieve text from existing option if possible, or we are blind
                    currentText = $select.find('option:selected').text();
                    if(!currentText || currentText === '-- Selecteer product --') currentText = "Onbekend Product";
                } else {
                    validProductCount++;
                    currentStock = productInList.aantal_voorraad || productInList.voorraad;
                }
            }

            // Re-populate options to refresh disabled states
            $select.empty();
            $select.append('<option value="">-- Selecteer product --</option>');

            self.productsData.forEach(prod => {
                const stock = prod.aantal_voorraad || prod.voorraad;
                const option = $('<option></option>')
                    .attr('value', prod.id)
                    .text(`${prod.product_naam || prod.naam} (${stock} beschikbaar)`)
                    .data('stock', stock);

                // Disable if selected elsewhere
                if (selectedIds.includes(prod.id) && prod.id !== currentVal) {
                    option.prop('disabled', true).addClass('bg-gray-100 text-gray-400');
                }

                if (prod.id === currentVal) {
                    option.prop('selected', true);
                }

                $select.append(option);
            });

            // Handle invalid product (not allowed for client)
            if (currentVal && !isValidForClient) {
                 const invalidOption = $('<option></option>')
                    .attr('value', currentVal)
                    .text(`${currentText} (Niet toegestaan)`)
                    .prop('selected', true)
                    .prop('disabled', true);

                $select.append(invalidOption);
                $select.addClass('border-red-500 bg-red-50 text-red-900').removeClass('border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300');
                $row.append('<p class="error-msg text-xs text-red-600 mt-1">Dit product mag niet voor deze klant. Verwijder of wijzig.</p>');
                invalidCount++;
            }
        });

        // Submit Button State
        const $submitBtn = $(this.submitBtnSelector);

        // Dirty Check
        const isDirty = this.getSnapshot() !== this.initialState;

        if (invalidCount > 0) {
            $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        } else if (validProductCount === 0) {
             // If valid products are 0, we can't submit anyway (unless deleting package is implied by empty? Assuming not)
            $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        } else if (!isDirty) {
             // No changes made
             $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        } else {
            $submitBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
        }
    }

    captureInitialState() {
        this.initialState = this.getSnapshot();
        // Force re-validation to update button state immediately
        this.updateProductDropdownsAndValidate();
    }

    getSnapshot() {
        const products = [];
        $(`#${this.containerId} .product-row`).each(function() {
            const $row = $(this);
            const pid = parseInt($row.find('select').val());
            const qty = parseInt($row.find('input[type="number"]').val());
            if (pid && qty) {
                products.push({ id: pid, qty: qty });
            }
        });
        // Sort by ID to ensure order doesn't affect "equality"
        products.sort((a, b) => a.id - b.id);
        return JSON.stringify(products);
    }

    showToast(message, type = 'error') {
        const colorClass = type === 'error' ? 'bg-red-500' : 'bg-green-500';
        const toast = $(`
            <div class="${colorClass} text-white px-6 py-4 rounded shadow-lg transform transition-all duration-300 translate-y-full opacity-0 flex items-center gap-2">
                <span>${message}</span>
                <button type="button" class="ml-4 font-bold close-toast">&times;</button>
            </div>
        `);

        toast.find('.close-toast').on('click', function() { $(this).parent().remove(); });

        $(`#${this.toastContainerId}`).append(toast);
        setTimeout(() => toast.removeClass('translate-y-full opacity-0'), 10);
        setTimeout(() => {
            toast.addClass('translate-y-full opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Method to load initial rows (for Edit)
    loadExistingProducts(products) {
        // products is array of {product_id, aantal}
        // However, we need to ensure productsData is loaded first!
        // This is tricky async.
        // It's better if handleKlantChange promise returns, then we call this.
    }
}
