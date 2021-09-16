// @flow

/**
 *
 */
export interface CaptchaAdapterInterface
{

    /**
     * Requests a new recaptcha token for the provided
     * action key and returns it as simple string.
     * @param {string} actionKey
     */
    requestToken(actionKey);

}