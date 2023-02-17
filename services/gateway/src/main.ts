import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { ValidationPipe } from '@nestjs/common'

async function bootstrap() {
  const app = await NestFactory.create(AppModule);
  app.useGlobalPipes(new ValidationPipe({
    // validationError.value: true,
    // stopAtFirstError: true,
    // transform: true,
  }));
  app.setGlobalPrefix('api');
  await app.listen(3000); // TODO: брать переменную из env
}
bootstrap();
