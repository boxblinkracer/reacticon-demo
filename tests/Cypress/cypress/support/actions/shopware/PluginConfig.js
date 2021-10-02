import AdminAPIClient from "Services/shopware/AdminAPIClient";

export default class PluginConfig {

    /**
     *
     * @param minScore
     */
    setMinScore(minScore) {
        const apiClient = new AdminAPIClient();
        const data = {
            "null": {
                "GoogleRecaptchaPlugin.config.registerMinScore": minScore,
            }
        };

        apiClient.post('/_action/system-config/batch', data);

        cy.wait(500);
    }

}
