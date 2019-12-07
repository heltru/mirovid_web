import {Menu} from './menu.js';
import {MenuItem} from './menu-item.js';
import {Main} from './main.js';
import {Tools} from './tools.js';
import {Tool} from './tool.js';
import {Canvas} from './canvas.js';
import {Palette} from './palette.js';
import {palettes} from './palettes/palettes.js';

export class App {
  constructor() {
    this.currentCursor = 'crosshair';
    document.body.style.cursor = this.currentCursor;
    this.currentTool = 'pencil';
    this.memories = [];
    this.imageName = 'pixel-art.png';

    this.element = document.createElement('div');
    this.element.setAttribute('id', 'app');
    document.body.appendChild(this.element);

    window.addEventListener('keydown', (event) => this.keyDown(event));
  }

  set color(color) {
    this.currentColor = color;
  }

  set tool(tool) {
    this.currentTool = tool;
    this.updateCursor(tool);
  }

  get cursor() {
    return this.currentCursor;
  }

  set cursor(cursor) {
    this.currentCursor = cursor;
    document.body.style.cursor = cursor;
  }

  set memory(memory) {
    this.memories.push(memory);
  }

  get currentPalette() {
    return this.palette.current;
  }

  set currentpPlette(index) {
    this.palette.current = index;
  }

  clickMenuItem(item) {
    const id = item.element.id;
    switch (id) {
      case 'newFile':
        let { context } = this.canvas;
        const newImage = context.createImageData(this.canvas.width, this.canvas.height);
        context.putImageData(newImage, 0, 0);
        this.memories = [];
        break;
      case 'download':
        item.link.setAttribute('download', `${this.imageName}`)
        const image = this.canvas.element
          .toDataURL('image/png')
          .replace('image/png', 'image/octet-stream');
        item.link.href = image;
        break;
      case 'upload':
        item.input.click();
        break;
      case 'undo':
        if(this.memories.length > 0) {
          const lastMemory = this.memories.pop();
          const x = lastMemory[1];
          const y = lastMemory[2];
          const color = lastMemory [3];
          this.canvas.paint(x, y, color);
          this.memories.pop();
        }
        break;
      case 'redo':
        break;
    }
  }

  keyDown(event) {
    if(
      event.ctrlKey &&
      event.key === 'z' &&
      this.memories.length > 0
      ) {
      const lastMemory = this.memories.pop();
      const x = lastMemory[1];
      const y = lastMemory[2];
      const color = lastMemory [3];
      this.canvas.paint(x, y, color);
      this.memories.pop();
    }
  }

  loadImage(image, result) {
    image.src = result;
    image.onload = () => {
      this.canvas.context.drawImage(image, 0, 0);
    }
  }

  processImage(event) {
    const { files } = event.srcElement;
    const file = files[0];
    const { name } = file;
    this.imageName = name;
    const reader = new FileReader();
    const image = new Image();
    reader.onload = () => {
      this.loadImage(image, reader.result)
    };
    reader.readAsDataURL(file);
  }

  updateCursor(tool) {
    switch (tool) {
      case 'pencil':
        document.body.style.cursor = 'crosshair';
        break;
      case 'dropper':
        document.body.style.cursor = 'crosshair';
        break;
      case 'zoom-in':
        document.body.style.cursor = 'zoom-in';
        break;
      case 'zoom-out':
        document.body.style.cursor = 'zoom-out';
        break;
      case 'pan':
        document.body.style.cursor = 'grab';
        break;
    }
  }
}

const app = new App();

const menu = new Menu(app);
const newFile = new MenuItem(menu, 'newFile', 'fa-file');
const download = new MenuItem(menu, 'download', 'fa-file-download');
const upload = new MenuItem(menu, 'upload', 'fa-file-upload');
const undo = new MenuItem(menu, 'undo', 'fa-undo-alt');
// const redo = new MenuItem(menu, 'redo', 'fa-redo-alt');
// const choosePalette = new MenuItem(menu, 'choosePalette', 'fa-palette');

const main = new Main(app);

const tools = new Tools(app);
const pencil = new Tool(tools, 'pencil', 'fa-pencil-alt');
const dropper = new Tool(tools, 'dropper', 'fa-eye-dropper');
const zoomIn = new Tool(tools, 'zoom-in', 'fa-search-plus');
const zoomOut = new Tool(tools, 'zoom-out', 'fa-search-minus');
const pan = new Tool(tools, 'pan', 'fa-hand-paper');

const canvas = new Canvas(main);

const palette = new Palette(app);
palette.current = 1;

canvas.zoom = 16;
