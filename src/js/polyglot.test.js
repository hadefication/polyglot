import trans from './polyglot.js';

beforeEach(() => {
    global.Polyglot = {
        validation: {
            required: 'The :attribute field is required.',
            params: 'This has a lot of params :param1, :param2, :param3 and :param4'
        }
    };
});

test(`trans('validation.required') should return "The :attribute field is required."`, () => expect(trans('validation.required')).toBe('The :attribute field is required.'));
test(`trans('validation.required', {attribute: 'email'}) should return "The email field is required."`, () => expect(trans('validation.required', {attribute: 'email'})).toBe('The email field is required.'));
test(`trans('validation.min') should return "validation.min"`, () => expect(trans('validation.min')).toBe('validation.min'));
test(`trans('validation.params', {param1: 1, param2: 2}) should return "This has a lot of params 1, 2, :param3 and :param4"`, () => expect(trans('validation.params', {param1: 1, param2: 2})).toBe('This has a lot of params 1, 2, :param3 and :param4'));
