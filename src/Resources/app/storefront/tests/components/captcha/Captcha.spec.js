import {Captcha} from "../../../src/js/components/Captcha";
import {FakeGoogleRecaptcha} from '../../fakes/FakeGoogleRecaptcha';


const fakeGoogleAdapter = new FakeGoogleRecaptcha('FAKE-TOKEN');
const recaptcha = new Captcha(fakeGoogleAdapter, 1);

// ----------------------------------------------------------------------------------------------

/**
 * This test verifies that the adapter token is correctly
 * returned from our component using the callback function.
 * This functionality is required to allow different adapters
 * to be used within our component.
 */
test('token from adapter should be returned in callback', (done) => {
    recaptcha.start('contact', (token) => {
        expect(token).toBe('FAKE-TOKEN');
        recaptcha.stop();
        done();
    });
});

/**
 * This test verifies that our recaptcha component
 * uses the provided action correctly by passing it on
 * to the current recaptcha adapter.
 * As soon as our loading has finished, we ask our fake adapter
 * what action it has received.
 */
test('action key should be passed on to adapter', (done) => {
    recaptcha.start('contact', () => {
        expect(fakeGoogleAdapter.getPassedAction()).toBe('contact');
        recaptcha.stop();
        done();
    });
});