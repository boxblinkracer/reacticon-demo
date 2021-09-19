import Plugin from 'src/plugin-system/plugin.class';
import DomAccess from 'src/helper/dom-access.helper';
import {CaptchaFactory} from '../factories/CaptchaFactory';


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

        const captchaTokenInputs = document.querySelectorAll('.captcha-form-token');

        if (captchaTokenInputs.length <= 0) {
            return;
        }

        this._siteKey = this.options.sitekey;

        const me = this;

        captchaTokenInputs.forEach(function (inputTextField) {

            const actionKey = DomAccess.getDataAttribute(inputTextField, 'data-captcha-action');

            me._prepareCaptcha(actionKey, inputTextField);
        });
    }


    /**
     * Creates a new captcha for the provided
     * action and input element
     *
     * @param {string} actionKey
     * @param {string} inputTextField
     * @private
     */
    _prepareCaptcha(actionKey, inputTextField) {

        const processToken = (token) => {
            if (inputTextField instanceof HTMLInputElement) {
                inputTextField.value = token;
            }
        };

        const captcha = CaptchaFactory.buildCaptcha(this._siteKey);

        captcha.start(actionKey, (token) => {
            processToken(token);
        });
    }

}
