import Devices from "Services/utils/Devices";
import Session from "Services/utils/Session"
import Shopware from "Services/shopware/Shopware";


const devices = new Devices();
const session = new Session();


const device = devices.getFirstDevice();
const shopware = new Shopware();


context("test", () => {


    beforeEach(() => {
        session.resetBrowserSession();
        devices.setDevice(device);
    });


    it('Test 123', () => {

        cy.visit('/');
    })

})
