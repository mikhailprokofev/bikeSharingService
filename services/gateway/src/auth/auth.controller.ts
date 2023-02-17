import { Controller, Get, Post, Body } from '@nestjs/common';
import { SignInDto, SignUpDto, RefreshDto } from './dto';

@Controller('auth')
export class AuthController {
  @Post('sign-in')
  signIn(@Body() signInDto: SignInDto): string {
    return 'This action returns all cats';
  }

  @Post('sign-up')
  signUn(@Body() signUpDto: SignUpDto): string {
    return 'This action returns all cats';
  }

  @Post('refresh')
  refresh(@Body() refreshDto: RefreshDto): string {
    return 'This action returns all cats';
  }

//   @Post('is-correct/token')
//   isCorrect(): string {
//     return 'This action returns all cats';
//   }
}