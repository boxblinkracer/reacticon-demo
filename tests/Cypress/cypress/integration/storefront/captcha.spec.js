import Devices from "Services/utils/Devices";
import Session from "Services/utils/Session"
import RegisterAction from "Actions/storefront/account/RegisterAction";
import PluginConfig from "Actions/shopware/PluginConfig";


const pluginConfig = new PluginConfig();

const devices = new Devices();
const session = new Session();
const register = new RegisterAction;

const device = devices.getFirstDevice();


context("Captcha Register Form", () => {

    beforeEach(() => {
        session.resetBrowserSession();
        devices.setDevice(device);
    });


    it('Captcha Token is filled', () => {

        pluginConfig.setMinScore(0.5);

        cy.visit('/account/login');
        cy.wait(2000);

        cy.get('.captcha-form-token').should('not.have.value', '')
    })

    it('Registration works with Captcha', () => {

        pluginConfig.setMinScore(0.5);

        cy.visit('/account/login');
        cy.wait(2000);

        register.registerRandomAccount();

        cy.contains('Default payment method');
    })

    it('Error on invalid captcha', () => {

        pluginConfig.setMinScore(0.5);

        cy.visit('/account/login');
        cy.wait(2000);

        // invalidate token
        cy.get('.captcha-form-token').then(function ($input) {
            $input[0].setAttribute('value', 'invalid-token')
        })

        // register
        register.registerRandomAccount();

        // we should have an error
        cy.contains('Captcha validation failed! Are you a bot?');
    })

    it('Error on invalid score', () => {

        pluginConfig.setMinScore(1.0);

        cy.visit('/account/login');
        cy.wait(2000);

        // register
        register.registerRandomAccount();

        // we should have an error
        cy.contains('Captcha validation failed! Are you a bot?');
    })

})
