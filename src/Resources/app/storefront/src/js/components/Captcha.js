

/**
 * This class handles the implemented recaptcha algorithm.
 * It creates a new token every x seconds and executes
 * the callback with the new token as argument.
 */
export class Captcha {

    /**
     * @type {CaptchaAdapterInterface}
     */
    _adapter;

    /**
     * @type {number}
     */
    _refreshSeconds;

    /**
     * @type {number}
     */
    _intervalId;

    /**
     *
     * @param {CaptchaAdapterInterface} adapter
     * @param {number} refreshSeconds
     */
    constructor(adapter, refreshSeconds) {
        this._adapter = adapter;
        this._refreshSeconds = refreshSeconds;
    }

    /**
     * Starts to request a new token from the adapter
     * and provides that token in a callback.
     *
     * @param {string} actionKey
     * @param {Promise} callback
     */
    async start(actionKey, callback) {
        // immediately load it
        const token = await this._adapter.requestToken(actionKey);
        callback(token);

        // auto refresh, every configured seconds
        this._intervalId = setInterval(async () => {
            const token = await this._adapter.requestToken(actionKey);
            callback(token);
        },
        this._refreshSeconds * 1000
        );
    }

    /**
     *
     */
    stop() {
        clearInterval(this._intervalId);
    }

}
