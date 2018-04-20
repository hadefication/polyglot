import { trans } from './trans.js';

beforeEach(() => {
    global.Polyglot = {
        translations: {
            en: {
                validation: {
                    required: 'The :attribute field is required.',
                    params: 'This has a lot of params :param1, :param2, :param3 and :param4'
                }
            },
            ja: {
                validation: {
                    required: 'The :attribute field is required.',
                    params: 'This has a lot of params :param1, :param2, :param3 and :param4'
                },
                "These credentials do not match our records.": "これらの資格情報は当社の記録と一致しません。"
            },
            es: {
                "These credentials do not match our records.": "Estas credenciales no coinciden con nuestros registros."
            }
        },
        activeLocale: 'en',
        fallbackLocale: 'en'
    };
});

test(`__('validation.required') should return "The :attribute field is required."`, () => expect(__('validation.required')).toBe('The :attribute field is required.'));
test(`__('validation.required', {attribute: 'email'}) should return "The email field is required."`, () => expect(__('validation.required', {attribute: 'email'})).toBe('The email field is required.'));
test(`__('validation.min') should return "validation.min"`, () => expect(__('validation.min')).toBe('validation.min'));
test(`__('validation.params', {param1: 1, param2: 2}) should return "This has a lot of params 1, 2, :param3 and :param4"`, () => expect(__('validation.params', {param1: 1, param2: 2})).toBe('This has a lot of params 1, 2, :param3 and :param4'));
test(`__('These credentials do not match our records.', 'ja') should return "これらの資格情報は当社の記録と一致しません。"`, () => expect(__('These credentials do not match our records.', 'ja')).toBe('これらの資格情報は当社の記録と一致しません。'));
test(`__('These credentials do not match our records.', 'es') should return "Estas credenciales no coinciden con nuestros registros."`, () => expect(__('These credentials do not match our records.', 'es')).toBe('Estas credenciales no coinciden con nuestros registros.'));
