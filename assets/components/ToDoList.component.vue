<link rel="stylesheet" href="../styles/app.css">
<template>
  <section class="todo display-block">
    <header class="display-flex align-items-center user-select-none">
      <h1 class="white">TODO</h1>
      <img :src="require(`../img/icons/${mode_icon}`)" alt="" class="height-100" @click="changeTheme">
    </header>
    <main>

      <div
          class="newtodo display-flex align-items-center"
      >
        <input
            type="checkbox"
            class="absolute"
            :id="`checkBox`"
        >
        <label
            :for="`checkBox`"
        ></label>
        <input
            type="text"
            placeholder="Add new todo"
            v-model="new_todo"
            @keyup.enter="addTodo"
        >
      </div>

      <ul>
        <li
            v-for="(todo, index) in todos"
            :key="index"
            class="display-flex align-items-center"
            @mouseover="showRemoveButton(index)"
            @mouseleave="showRemoveButton(index, true)"
        >
          <input
              type="checkbox"
              :checked="todo.done"
              @change="toggleDone(index)"
              class="absolute"
              :id="`checkBox${index}`"
          >
          <label
              :for="`checkBox${index}`"
              :id="`hoverCheckBox${index}`"
              @mouseover="hoverCheckBox(index)"
              @mouseleave="hoverCheckBox(index, true)"
          ></label>

          <input
              type="text"
              :class="{ done: todo.done }"
              @click="selectInput(index)"
              class="left"
              @keyup.enter="updateToDo(index)"
              :value="todo.text"
          >
          <button
              @click="removeTodo(index)"
              @mouseover="showRemoveButton(index)"
              @mouseleave="showRemoveButton(index, true)"
              class="user-select-none"
          >
            <img
                src="@/img/icons/icon-cross.svg"
                alt=""
                :id="`remove${index}`"
                class="opacity-0"
            >
          </button>

        </li>
        <li
            class="filter display-flex user-select-none"
        >
          <p>{{todos.length}} items left ({{todos.filter(i => i.done).length}} Done)</p>
          <p>
            <span>
              <a
                  href="#"
                  @click="filterTodos('all')"
                  :class="{ filter_active: current_filter === 'all' }"
              >
                All
              </a>
              <a
                  href="#"
                  @click="filterTodos('active')"
                  :class="{ filter_active: current_filter === 'active' }"
              >
                Active
              </a>
              <a
                  href="#"
                  @click="filterTodos('completed')"
                  :class="{ filter_active: current_filter === 'completed' }"
              >
                Completed
              </a>
            </span>
          </p>
          <p>
            <a
                href="#"
                @click="clearCompleted"
            >
              Clear Completed
            </a>
          </p>
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
      current_filter: "all",
      theme: "",
      icons: {
        icon_dark: "icon-moon.svg",
        icon_light: "icon-sun.svg",
      },
      todos: [],
      unfiltered_todos: []
    }
  },
  beforeMount() {
    const prefersDarkMode = window.matchMedia("(prefers-color-scheme:dark)").matches
    this.mode_icon = prefersDarkMode ? this.icons.icon_light : this.icons.icon_dark;
  },
  mounted() {
    this.theme = window.getComputedStyle(document.documentElement).getPropertyValue("--light") === ' ' ? 'dark' : 'light';
    document.documentElement.classList.add(this.theme);
    this.unfiltered_todos = this.todos;

    this.getCookie();
  },
  methods: {
    getCookie() {
      const cookie = document.cookie.split(';').find(c => c.trim().startsWith('todos='));
      if (cookie) {
        this.todos = JSON.parse(cookie.split('=')[1]);
        this.unfiltered_todos = this.todos;
      }else {
        document.cookie = `todos=${JSON.stringify([])};max-age=3600`;
      }
    },
    updateCookie() {
      document.cookie = `todos=${JSON.stringify(this.todos)};max-age=3600`;
      this.getCookie();
    },
    addTodo() {
      const done = this.$el.querySelector("input[type='checkbox']").checked;

      const obj = {
        text: this.new_todo,
        done: done
      }

      this.todos.push(obj);
      this.unfiltered_todos = this.todos;
      this.updateCookie();


      this.new_todo = "";
      this.$el.querySelector("input[type='checkbox']").checked = false;
    },
    removeTodo(index) {
      this.todos.splice(index, 1);
      this.updateCookie();
    },
    changeTheme() {
      const root = document.documentElement;
      root.classList.remove(this.theme);
      this.theme = this.theme === 'light' ? 'dark' : 'light';
      (this.theme === 'light') ? this.mode_icon = this.icons.icon_dark : this.mode_icon = this.icons.icon_light;
      root.classList.add(this.theme);
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

      this.updateCookie();
    },
    toggleDone(index) {
      this.todos[index].done = !this.todos[index].done;
      this.updateCookie();
      this.getTextInput(index).classList.toggle("done");
    },
    selectInput(index) {
      this.getTextInput(index).setSelectionRange(0, this.getTextInput(index).value.length)
    },
    filterTodos(filter) {
      this.current_filter = filter;
      if (filter === "all") {
        this.todos = this.unfiltered_todos;
        return;
      }
      if (filter === "active") {
        this.todos = this.unfiltered_todos.filter(todo => !todo.done);
        return;
      }
      if (filter === "completed") {
        this.todos = this.unfiltered_todos.filter(todo => todo.done);
      }
    },
    clearCompleted() {
      this.todos = this.todos.filter(todo => !todo.done);
      this.updateCookie();
    },
    showRemoveButton(index, remove = false) {
      const button = document.getElementById(`remove${index}`)
      if(remove) button.classList.remove("show");
      else button.classList.add("show");
    },
    hoverCheckBox(index, remove = false) {
      const checkBox = document.getElementById(`hoverCheckBox${index}`)
      if(remove) checkBox.classList.remove("hover");
      else checkBox.classList.add("hover");
    }
  }
}
</script>

<style scoped>
@import url('@/styles/todo/todo.css');
</style>