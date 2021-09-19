/**
 * This class is as an empty adapter
 * that will be used if we dont have
 * a valid captcha configuration.
 */
export class NullCaptchaAdapter {

    /**
     *
     * @param {string} actionKey
     */
    // eslint-disable-next-line no-unused-vars
    async requestToken(actionKey) {
        return 'not-configured';
    }

}
