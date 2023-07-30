import {Command} from './Command.js';
import {ConcreteCommand} from './Command.js';
import {Receiver} from './Command.js';
import {Invoker} from './Command.js';


// Используем созданные классы
const receiver = new Receiver();
const command1 = new ConcreteCommand(receiver, 'Command 1');
const command2 = new ConcreteCommand(receiver, 'Command 2');

const invoker = new Invoker();
invoker.storeAndExecute(command1); // Receiver: Command 1
invoker.storeAndExecute(command2); // Receiver: Command 2

