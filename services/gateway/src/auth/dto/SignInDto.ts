import {IsEmail, IsNotEmpty, Matches, MinLength} from 'class-validator';

export class SignInDto {
    @IsEmail()
    readonly email: string;

    @IsNotEmpty()
    @MinLength(6, {
        message: 'Password is too short. Minimal length is $constraint1',
    })
    // TODO: Должен ли пароль минимум одну букву и минимум одну цифру содержать
    @Matches(/^\w+$/, {
        message: 'password should contain only letters and numbers',
    })
    readonly password: string;
}
