import Plugin from 'src/plugin-system/plugin.class';
import DomAccess from 'src/helper/dom-access.helper';
import {CaptchaFactory} from "../factories/CaptchaFactory";


/**
 *
 */
export default class CaptchaPlugin extends Plugin {

    /**
     * @type {string}
     */
    _siteKey;


    /**
     *
     */
    init() {

        console.log('Init Google Recaptcha');

        this._siteKey = this.options.sitekey;

        let captchaForms = document.querySelectorAll('.captcha-form');

        if (captchaForms.length <= 0) {
            return;
        }

        const me = this;

        captchaForms.forEach(function (form) {

            const actionKey = DomAccess.getDataAttribute(form, 'data-captcha-action');
            const inputFieldClass = DomAccess.getDataAttribute(form, 'data-captcha-input');

            me._prepareCaptcha(actionKey, inputFieldClass);
        });
    }


    /**
     * Creates a new captcha for the provided
     * action and input element
     *
     * @param {string }actionKey
     * @param {string} inputClass
     * @private
     */
    _prepareCaptcha(actionKey, inputClass) {

        const processToken = (token) => {

            const field = this._document.getElementsByClassName(inputClass)[0];

            if (field instanceof HTMLInputElement) {
                field.value = token;
            }
        };

        const captcha = CaptchaFactory.buildCaptcha(this._siteKey);

        captcha.start(actionKey, (token) => {
            processToken(token);
        });
    }

}
