import { trans, trans_choice } from './polyglot.js';

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
                    },

                    message: {
                        'apples': 'There is one apple|There are many apples',
                        'oranges': '{0} There are none|[1,19] There are some|[20,*] There are many',
                        'minutes_ago': '{1} :value minute ago|[2,*] :value minutes ago',
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

test(`trans('validation.required') should return "The :attribute field is required."`, () => { 
    expect(trans('validation.required')).toBe('The :attribute field is required.');
});

test(`trans('validation.required', {attribute: 'email'}) should return "The email field is required."`, () => {
    expect(trans('validation.required', {attribute: 'email'})).toBe('The email field is required.');
});

test(`trans('validation.min') should return "validation.min"`, () => {
    expect(trans('validation.min')).toBe('validation.min');
});
test(`trans('validation.params', {param1: 1, param2: 2}) should return "This has a lot of params 1, 2, :param3 and :param4"`, () => {
    expect(trans('validation.params', {param1: 1, param2: 2})).toBe('This has a lot of params 1, 2, :param3 and :param4');
});

test(`trans_choice('message.apples', 1) should return "There is one apple"`, () => {
    expect(trans_choice('message.apples', 1)).toBe('There is one apple');
});

test(`trans_choice('message.oranges', 2) should return "There are some"`, () => {
    expect(trans_choice('message.oranges', 1)).toBe('There are some');
});

test(`trans_choice('message.minutes_ago', 2, {value: 2}) should return "There are some"`, () => {
    expect(trans_choice('message.minutes_ago', 2, {value: 2})).toBe('2 minutes ago');
});