const {join, resolve} = require('path');

module.exports = () => {
    return {
        resolve: {
            alias: {
                'recaptcha-v3': resolve(
                    join(__dirname, '..', 'node_modules', 'recaptcha-v3')
                )
            }
        }
    };
}
