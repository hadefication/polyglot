import { trans } from './polyglot.js';

beforeEach(() => {
    global.Polyglot = {
        settings: {
            locale: 'en',
            fallback: 'en'
        },
        translations: {
            en: {
                keys: {
                    validation: {
                        required: 'The :attribute field is required.',
                        params: 'This has a lot of params :param1, :param2, :param3 and :param4'
                    }
                },
                strings: {}
            },
            es: {
                keys: {
    
                },
                strings: {}
            }
        }
    };
});

test(`trans('validation.required') should return "The :attribute field is required."`, () => expect(trans('validation.required')).toBe('The :attribute field is required.'));
test(`trans('validation.required', {attribute: 'email'}) should return "The email field is required."`, () => expect(trans('validation.required', {attribute: 'email'})).toBe('The email field is required.'));
test(`trans('validation.min') should return "validation.min"`, () => expect(trans('validation.min')).toBe('validation.min'));
test(`trans('validation.params', {param1: 1, param2: 2}) should return "This has a lot of params 1, 2, :param3 and :param4"`, () => expect(trans('validation.params', {param1: 1, param2: 2})).toBe('This has a lot of params 1, 2, :param3 and :param4'));
