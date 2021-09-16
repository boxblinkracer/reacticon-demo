import {load} from 'recaptcha-v3';

/**
 * This class is used as an adapter for the
 * imported google recaptcha-v3 component.
 * It loads the component and starts requesting a key.
 */
export class GoogleRecaptcha3Adapter {

    /**
     * @type {string}
     */
    siteKey;


    /**
     *
     * @param siteKey
     */
    constructor(siteKey) {
        this.siteKey = siteKey;
    }

    /**
     *
     * @param {string} actionKey
     */
    async requestToken(actionKey) {
        let receivedToken = '';

        await load(this.siteKey).then(async (recaptcha) => {
            await recaptcha.execute(actionKey).then((token) => {
                receivedToken = token;
            })
        });

        return receivedToken;
    }

}
