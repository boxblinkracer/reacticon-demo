
/**
 *
 */
export class FakeGoogleRecaptcha {

    token;
    passedAction;

    /**
     *
     * @param {string} token
     */
    constructor(token) {
        this.token = token;
        this.passedAction = '';
    }

    /**
     *
     * @returns {string}
     */
    getPassedAction() {
        return this.passedAction;
    }

    /**
     *
     * @param {string} actionKey
     * @returns {Promise<string>}
     */
    async requestToken(actionKey) {
        this.passedAction = actionKey;
        return this.token;
    }

}

