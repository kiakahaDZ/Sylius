/**
 * YalidineAddressController — handles DZ wilaya + commune selection at checkout.
 *
 * When the user selects Algeria (DZ) as country, this controller:
 *  1. Hides the default province/city fields
 *  2. Shows a wilaya dropdown (loaded from Yalidine API)
 *  3. On wilaya selection, loads communes and shows a fee estimate
 *  4. Writes wilaya_id → provinceCode, wilaya name → provinceName, commune → city
 */

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'countrySelect',
        'dzFields',
        'standardCity',
        'standardProvince',
        'wilayaSelect',
        'communeSelect',
        'feeDisplay',
        'provinceCode',
        'provinceName',
        'cityHidden',
    ];

    static values = {
        wilayasUrl:  { type: String, default: '/yalidine/api/wilayas' },
        communesUrl: { type: String, default: '/yalidine/api/communes' },
        feesUrl:     { type: String, default: '/yalidine/api/fees' },
    };

    connect() {
        this._wilayaCache   = null;
        this._communeCache  = {};
        this._onCountryChange();
    }

    // ─── Event handlers ────────────────────────────────────────────────────

    countryChanged() {
        this._onCountryChange();
    }

    async wilayaChanged() {
        const wilayaId   = this.wilayaSelectTarget.value;
        const wilayaName = this.wilayaSelectTarget.options[this.wilayaSelectTarget.selectedIndex]?.text ?? '';

        if (this.hasProvinceCodeTarget) this.provinceCodeTarget.value = wilayaId;
        if (this.hasProvinceNameTarget) this.provinceNameTarget.value = wilayaName;

        // Reset commune
        this._setCommunes([]);
        if (this.hasCityHiddenTarget) this.cityHiddenTarget.value = '';

        if (!wilayaId) return;

        await this._loadCommunes(parseInt(wilayaId, 10));
        await this._loadFee(parseInt(wilayaId, 10));
    }

    communeChanged() {
        const communeName = this.communeSelectTarget.options[this.communeSelectTarget.selectedIndex]?.text ?? '';
        if (this.hasCityHiddenTarget) this.cityHiddenTarget.value = communeName;
    }

    // ─── Private ───────────────────────────────────────────────────────────

    _isDz() {
        if (!this.hasCountrySelectTarget) return false;
        return this.countrySelectTarget.value === 'DZ';
    }

    async _onCountryChange() {
        const isDz = this._isDz();

        // Show/hide DZ-specific block
        if (this.hasDzFieldsTarget) {
            this.dzFieldsTarget.classList.toggle('d-none', !isDz);
        }

        // Show/hide standard city + province rows
        if (this.hasStandardCityTarget) {
            this.standardCityTarget.classList.toggle('d-none', isDz);
            // Disable native input when DZ so it doesn't submit conflict
            const input = this.standardCityTarget.querySelector('input');
            if (input) input.disabled = isDz;
        }
        if (this.hasStandardProvinceTarget) {
            this.standardProvinceTarget.classList.toggle('d-none', isDz);
            const input = this.standardProvinceTarget.querySelector('input, select');
            if (input) input.disabled = isDz;
        }

        if (isDz && !this._wilayaCache) {
            await this._loadWilayas();

            // Pre-select existing provinceCode if set (address book re-use)
            const existing = this.hasProvinceCodeTarget ? this.provinceCodeTarget.value : '';
            if (existing && this.hasWilayaSelectTarget) {
                this.wilayaSelectTarget.value = existing;
                if (this.wilayaSelectTarget.value === existing) {
                    await this.wilayaChanged();
                }
            }
        }

        if (!isDz) {
            // Re-enable native inputs when switching away from DZ
            if (this.hasProvinceCodeTarget) this.provinceCodeTarget.value = '';
            if (this.hasProvinceNameTarget) this.provinceNameTarget.value = '';
            if (this.hasCityHiddenTarget)   this.cityHiddenTarget.value   = '';
        }
    }

    async _loadWilayas() {
        try {
            const res      = await fetch(this.wilayasUrlValue);
            const wilayas  = await res.json();
            this._wilayaCache = wilayas;
            this._populateWilayas(wilayas);
        } catch (e) {
            console.error('[Yalidine] Failed to load wilayas', e);
        }
    }

    async _loadCommunes(wilayaId) {
        if (this._communeCache[wilayaId]) {
            this._setCommunes(this._communeCache[wilayaId]);
            return;
        }
        try {
            const res      = await fetch(`${this.communesUrlValue}?wilaya_id=${wilayaId}`);
            const communes = await res.json();
            this._communeCache[wilayaId] = communes;
            this._setCommunes(communes);
        } catch (e) {
            console.error('[Yalidine] Failed to load communes', e);
        }
    }

    async _loadFee(wilayaId) {
        if (!this.hasFeeDisplayTarget) return;
        this.feeDisplayTarget.textContent = '…';
        this.feeDisplayTarget.closest('[data-fee-wrapper]')?.classList.remove('d-none');

        try {
            const res  = await fetch(`${this.feesUrlValue}?to_wilaya_id=${wilayaId}`);
            const data = await res.json();

            if (data.home_fee !== undefined || data.desk_fee !== undefined) {
                const home = data.home_fee ?? '—';
                const desk = data.desk_fee ?? '—';
                this.feeDisplayTarget.innerHTML =
                    `<span class="me-3">🏠 ${home} DA</span><span>🏢 ${desk} DA</span>`;
            } else if (data.error) {
                this.feeDisplayTarget.textContent = '—';
            } else {
                this.feeDisplayTarget.textContent = JSON.stringify(data);
            }
        } catch (e) {
            this.feeDisplayTarget.textContent = '—';
        }
    }

    _populateWilayas(wilayas) {
        if (!this.hasWilayaSelectTarget) return;
        const sel = this.wilayaSelectTarget;
        sel.innerHTML = `<option value="">${sel.dataset.placeholder ?? '— Wilaya —'}</option>`;

        wilayas.forEach((w) => {
            const opt   = document.createElement('option');
            opt.value   = String(w.wilaya_id ?? w.id ?? '');
            opt.textContent = w.name;
            sel.appendChild(opt);
        });
    }

    _setCommunes(communes) {
        if (!this.hasCommuneSelectTarget) return;
        const sel = this.communeSelectTarget;
        sel.innerHTML = `<option value="">${sel.dataset.placeholder ?? '— Commune —'}</option>`;

        communes.forEach((c) => {
            const opt   = document.createElement('option');
            opt.value   = String(c.commune_id ?? c.id ?? c.name ?? '');
            opt.textContent = c.name;
            sel.appendChild(opt);
        });
    }
}
