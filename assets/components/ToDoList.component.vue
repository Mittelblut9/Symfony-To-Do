<template>
  <section class="todo">
    <header class="display-flex align-items-center">
      <h1 class="white">TODO</h1>
      <img :src="require(`../img/icons/${mode_icon}`)" alt="" class="height-100" @click="changeTheme">
    </header>
    <main>

      <div class="display-flex align-items-center">
        <input type="checkbox" class="absolute" :id="`checkBox`">
        <label :for="`checkBox`"></label>
        <input type="text" placeholder="Add new todo" v-model="new_todo" @keyup.enter="addTodo">
      </div>

      <ul>
        <li v-for="(todo, index) in todos" :key="index" class="display-flex align-items-center">
          <input type="checkbox" :checked="todo.done" @change="toggleDone(index)" class="absolute"
                 :id="`checkBox${index}`">
          <label :for="`checkBox${index}`"></label>
          <input type="text" :class="{ done: todo.done }" @click="selectInput(index)" class="left" @keyup.enter="updateToDo(index)" :value="todo.text">
          <button @click="removeTodo">
            <img src="@/img/icons/icon-cross.svg" alt="">
          </button>

        </li>
      </ul>
    </main>
  </section>
</template>

<script>
export default {
  name: "ToDoList",
  data() {
    return {
      new_todo: "",
      mode_icon: "",
      icons: {
        icon_dark: "icon-moon.svg",
        icon_light: "icon-sun.svg",
      },
      todos: [
        {text: "Learn Vue", done: false},
        {text: "Learn React", done: false},
        {text: "Learn Angular", done: false},
      ],
    }
  },
  beforeMount() {
    const prefersDarkMode = window.matchMedia("(prefers-color-scheme:dark)").matches
    this.mode_icon = prefersDarkMode ? this.icons.icon_light : this.icons.icon_dark;
  },
  methods: {
    addTodo() {
      const done = this.$el.querySelector("input[type='checkbox']").checked;

      this.todos.push({text: this.new_todo, done});
      this.new_todo = "";
      this.$el.querySelector("input[type='checkbox']").checked = false;
    },
    changeTheme() {
      const prefersDarkMode = localStorage.getItem("theme");

      if (prefersDarkMode === 'dark') {
        this.mode_icon = this.icons.icon_dark;
        document.documentElement.setAttribute("data-theme", "light")
        localStorage.setItem("theme", "light");
        return;
      }
      this.mode_icon = this.icons.icon_light;
      document.documentElement.setAttribute("data-theme", "dark");
      localStorage.setItem("theme", "dark");

    },
    removeTodo(index) {
      this.todos.splice(index, 1);
    },
    getTextInput(index) {
      return document.getElementById(`checkBox${index}`).nextElementSibling.nextElementSibling;
    },
    updateToDo(index) {
      const text = this.getTextInput(index).value;
      if(text === ' ' || text === '') {
        return this.removeTodo(index);
      }
      this.todos[index].text = text;
      this.getTextInput(index).blur();
    },
    toggleDone(index) {
      this.todos[index].done = !this.todos[index].done;

      this.getTextInput(index).classList.toggle("done");
    },
    selectInput(index) {
      this.getTextInput(index).setSelectionRange(0, this.getTextInput(index).value.length)
    }
  }
}
</script>

<style scoped>
@import url('@/styles/todo/todo.css');
</style>