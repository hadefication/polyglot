import trans from './trans.js';

beforeEach(() => {
    global.__TRANSLATIONS__ = {
        validation: {
            required: 'The :attribute field is required.',
            params: 'This has a lot of params :param1, :param2, :param3 and :param4'
        }
    };
});

test('trans is defined', () => {
    expect(trans).toBeDefined();
});

test('trans will translate a given translation key', () => {
    expect(trans('validation.required')).toBe('The :attribute field is required.');
});

test('trans will translate a given translation key and parse the supplied param too', () => {
    expect(trans('validation.required', {attribute: 'email'})).toBe('The email field is required.');
});

test('trans will return the supplied translation key if it\'s non existent', () => {
    expect(trans('validation.min')).toBe('validation.min');
});

test('trans will only parse params that exists in the translation', () => {
    expect(trans('validation.params', {param1: 1, param2: 2})).toBe('This has a lot of params 1, 2, :param3 and :param4');
});
