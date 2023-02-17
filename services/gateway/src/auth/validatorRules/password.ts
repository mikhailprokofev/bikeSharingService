import { registerDecorator, ValidationOptions, ValidationArguments } from 'class-validator';

export function IsPassword(property: string, validationOptions?: ValidationOptions) {
    return function (object: Object, propertyName: string) {
        registerDecorator({
            name: 'isPassword',
            target: object.constructor,
            propertyName: propertyName,
            constraints: [property],
            options: validationOptions,
            validator: {
                validate(value: any, args: ValidationArguments) {
                    return typeof value === 'string';
                },
            },
        });
    };
}
