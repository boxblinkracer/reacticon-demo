import RegisterRepository from 'Repositories/storefront/account/RegisterRepository';

const repo = new RegisterRepository();

export default class RegisterAction {

    /**
     *
     */
    registerRandomAccount() {

        const email = this._generateString(10) + "@localhost.com";
        const password = this._generateString(20);

        repo.getSalutation().select('Mr.');

        repo.getFirstname().clear().type('Reacticon');
        repo.getLastname().clear().type('Reacticon');

        repo.getEmail().clear().type(email);
        repo.getPassword().clear().type(password);

        repo.getStreet().clear().type('Reacticon');
        repo.getZipcode().clear().type('Reacticon');
        repo.getCity().clear().type('Reacticon');

        repo.getCountry().select('Germany');

        repo.getRegisterButton().click();
    }

    /**
     *
     * @param length
     * @returns {string}
     * @private
     */
    _generateString(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        return result;
    }

}
