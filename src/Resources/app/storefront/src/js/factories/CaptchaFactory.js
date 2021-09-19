import {NullCaptchaAdapter} from '../components/adapter/NullCaptchaAdapter';
import {GoogleRecaptcha3Adapter} from '../components/adapter/GoogleRecaptcha3Adapter';
import {Captcha} from '../components/Captcha';

/**
 *
 */
export class CaptchaFactory {

    /**
     *
     * @param {string} siteKey
     * @returns {Captcha}
     */
    static buildCaptcha(siteKey) {

        let recaptchaAdapter = new NullCaptchaAdapter();

        if (siteKey !== '') {
            recaptchaAdapter = new GoogleRecaptcha3Adapter(siteKey);
        }

        return new Captcha(recaptchaAdapter, 30);
    }

}
