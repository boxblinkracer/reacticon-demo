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
    async requestToken(actionKey) {
        return 'not-configured';
    }

}
