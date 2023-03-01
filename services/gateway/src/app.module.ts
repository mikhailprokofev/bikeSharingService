import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import { ConfigModule } from '@nestjs/config';
import { AuthModule } from './auth/auth.module';
import configuration from './config/configuration';

@Module({
  imports: [AuthModule, ConfigModule.forRoot({
    load: [configuration],
  })],
  controllers: [AppController],
  providers: [AppService],
})
export class AppModule {}
