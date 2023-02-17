import {IsEmail, IsNotEmpty, MinLength, Matches} from "class-validator";

export class SignUpDto {
    @IsEmail()
    readonly email: string;

    @IsNotEmpty()
    readonly full_name: string;

    @IsNotEmpty()
    @MinLength(6, {
        message: 'password is too short. Minimal length is $constraint1',
    })
    // TODO: Должен ли пароль минимум одну букву и минимум одну цифру содержать
    @Matches(/^\w+$/, {
        message: 'password should contain only letters and numbers',
    })
    readonly password: string;
}
