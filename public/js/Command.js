export class Command {
  constructor(receiver) {
    this.receiver = receiver;
  }
  
  execute() {}
}

// Создаем класс конкретной команды
export class ConcreteCommand extends Command {
  constructor(receiver, args) {
    super(receiver);
    this.args = args;
  }
  
  execute() {
    this.receiver.action(this.args);
  }
}

// Создаем класс получателя
export class Receiver {
  action(args) {
      console.log(`Receiver: ${args}`);
  }
}

// Создаем класс инициатора
export class Invoker {
  constructor() {
    this.commands = [];
  }
  
  storeAndExecute(command) {
    this.commands.push(command);
    command.execute();
  }
}
