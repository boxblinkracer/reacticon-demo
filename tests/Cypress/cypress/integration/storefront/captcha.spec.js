import Devices from "Services/utils/Devices";
import Session from "Services/utils/Session"
import RegisterAction from "Actions/storefront/account/RegisterAction";


const devices = new Devices();
const session = new Session();
const register = new RegisterAction;

const device = devices.getFirstDevice();


context("Captcha Register Form", () => {

    beforeEach(() => {
        session.resetBrowserSession();
        devices.setDevice(device);
    });


    it('Registration works with Captcha', () => {
        cy.visit('/account/login');
        cy.wait(1000);

        register.registerRandomAccount();

        cy.contains('Default payment method');
    })

    it('Error on invalid captcha', () => {

        cy.visit('/account/login');

        cy.wait(1000);

        // invalidate token
        cy.get('.captcha-form-token').then(function ($input) {
            $input[0].setAttribute('value', 'invalid-token')
        })

        // register
        register.registerRandomAccount();

        // we should have an error
        cy.contains('Captcha validation failed! Are you a bot?');
    })

})
