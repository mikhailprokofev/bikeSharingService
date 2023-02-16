import { Controller, Get, Post, Body } from '@nestjs/common';
import { SignInDto } from './dto/SignInDto';
import { SignUpDto } from './dto/SignUpDto';
import { RefreshDto } from './dto/RefreshDto';

@Controller('auth')
export class AuthController {
  @Post()
  signIn(@Body() signInDto: SignInDto): string {
    return 'This action returns all cats';
  }

  @Post()
  signUn(@Body() signUpDto: SignUpDto): string {
    return 'This action returns all cats';
  }

  @Post()
  refresh(@Body() refreshDto: RefreshDto): string {
    return 'This action returns all cats';
  }

//   @Post()
//   isCorrect(): string {
//     return 'This action returns all cats';
//   }
}