<template>
  <div>
    <div class="d-flex justify-content-between mb-4">
      <div class="d-flex justify-content-start">
        <div>
          <b-pagination
            class="mb-0"
            v-model="currentPage"
            :total-rows="rows"
            :per-page="perPage"
            aria-controls="my-table"
          ></b-pagination>
        </div>
        <div class="m-1 ml-3">
          <h4>Colors</h4>
        </div>
      </div>
      <div class="d-flex justify-content-end">
        <div>
          <div class="d-flex justify-content-end">
            <div class="m-1 mr-3"></div>
            <div>
              <b-button-group class="mx-1">
                <b-button variant="warning" :disabled="selection.length != 1" @click="edit()">edit</b-button>
                <b-button variant="danger" :disabled="selection.length == 0" @click="del()">Delete</b-button>
                <b-button variant="primary" v-b-modal.newColor>New Color</b-button>
                <b-dropdown variant="info" text="extra">
                  <b-dropdown-item @click="read()">Reload</b-dropdown-item>
                  <b-dropdown-item @click="selectAllRows()">Select All</b-dropdown-item>
                  <b-dropdown-item @click="clearSelected()">Select None</b-dropdown-item>
                </b-dropdown>
              </b-button-group>
            </div>
          </div>
        </div>
      </div>

      <b-modal id="deleteColors" ref="deleteColors" title="Delete Colors" hide-footer>
        <h5>Are you shure to delete {{selection.length}} color(s)</h5>
        <h6>You are about to delete:</h6>
        <form @submit.prevent="destroy()">
          <ul v-for="selected in selection" v-bind:key="selected.id">
            <li>{{selected.name}} {{selected.waterless ? "- Waterless" : ""}} {{selected.rise ? "- Rise" : ""}}</li>
          </ul>

          <b-button block type="submit" variant="danger">Delete!</b-button>
        </form>
      </b-modal>

      <b-modal id="newColor" ref="newColor" title="New Color" hide-footer>
        <form @submit.prevent="create()">
          <b-form-group id="input-group-price" label="Name:" label-for="price">
            <b-form-input
              v-model="form.name"
              type="text"
              name="name"
              required
              placeholder="Enter Name"
            ></b-form-input>
          </b-form-group>
          <b-form-group label="Variant">
            <b-form-checkbox v-model="form.waterless" name="check-button" value="1" switch>
              Waterless
              <b>(Checked: {{ form.waterless }})</b>
            </b-form-checkbox>
            <b-form-checkbox v-model="form.rise" name="check-button" value="1" switch>
              Rise
              <b>(Checked: {{ form.rise }})</b>
            </b-form-checkbox>
          </b-form-group>
          <b-button block type="submit" variant="primary">Submit</b-button>
        </form>
      </b-modal>
    </div>
    <b-table
      ref="selectableColorsTable"
      selectable
      selected-variant="active"
      @row-selected="onRowSelected"
      small
      hover
      responsive="sm"
      :items="colors"
      :fields="fields"
      :per-page="perPage"
      :current-page="currentPage"
      :busy="mute"
    >
      <!-- <template v-slot:cell(waterless)="row">{{row.item.waterless ? "Waterless" : "-"}}</template>
      <template v-slot:cell(rise)="row">{{row.item.rise ? "Rise" : "-"}}</template>-->
      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>
    </b-table>
    <b-modal id="editColor" ref="editColor" title="Edit Color" hide-footer>
      <form @submit.prevent="update()">
        <b-form-group id="input-group-price" label="Name:" label-for="price">
          <b-form-input
            v-model="form.name"
            type="text"
            name="color"
            required
            placeholder="Enter Name"
          ></b-form-input>
        </b-form-group>
        <b-form-group label="Variant">
          <b-form-checkbox
            v-model="form.waterless"
            name="waterless"
            value="1"
            unchecked-value="0"
            switch
          >
            Waterless
            <b>(Checked: {{ form.waterless }})</b>
          </b-form-checkbox>
          <b-form-checkbox v-model="form.rise" name="rise" value="1" unchecked-value="0" switch>
            Rise
            <b>(Checked: {{ form.rise }})</b>
          </b-form-checkbox>
        </b-form-group>
        <b-button block type="warning" variant="primary">Update Color</b-button>
      </form>
    </b-modal>
  </div>
</template>

<script>
function Color({ id, name, waterless, rise }) {
  this.id = id;
  this.name = name;
  this.waterless = waterless;
  this.rise = rise;
}

export default {
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
      mute: false,
      perPage: 10,
      currentPage: 1,
      selection: [],
      form: [],
      form: { waterless: [], rise: [] },
      colors: [],
      fields: [
        { key: "id", sortable: true },
        { key: "name", sortable: true },
        { key: "waterless", sortable: true },
        { key: "rise", sortable: true }
      ]
    };
  },
  methods: {
    selectAllRows() {
      this.$refs.selectableColorsTable.selectAllRows();
    },
    clearSelected() {
      this.$refs.selectableColorsTable.clearSelected();
    },
    onRowSelected(items) {
      this.selection = items;
    },
    create() {
      this.$refs["newColor"].hide();
      window.axios
        .post("/api/colors", {
          _token: this.csrf,
          name: this.form.name,
          waterless: this.form.waterless[0],
          rise: this.form.rise[0]
        })
        .then(({ data }) => {
          this.$emit(
            "toast",
            "success",
            "Create",
            "Color: " + this.name + " Created"
          );

          this.colors.push(new Color(data));
        });
    },
    read() {
      this.mute = true;
      window.axios.get("/api/colors").then(({ data }) => {
        this.colors = [];
        data.forEach(color => {
          this.colors.push(new Color(color));
        });
        this.mute = false;
      });
    },
    edit() {
      this.form = this.selection[0];
      console.log(this.form);
      this.$refs["editColor"].show();
    },
    update() {
      var form = this.form;
      window.axios
        .put(`/api/colors/${this.form.id}`, {
          name: form.name,
          waterless: form.waterless,
          rise: form.rise
        })
        .then(() => {
          this.$refs["editColor"].hide();
          this.$emit(
            "toast",
            "warning",
            "Update",
            "Color: " + name + " Updated"
          );
          // this.colors.find(color => color.id === form.id).name = form.name;
          // this.colors.find(color => color.id === form.id).waterless =
          //   form.waterless;
          // this.colors.find(color => color.id === form.id).rise = form.rise;
          this.form = [];
        });
    },
    del() {
      this.$refs["deleteColors"].show();
    },
    destroy() {
      this.selection.forEach(selected => {
        this.$refs["deleteColors"].hide();
        window.axios.delete(`/api/colors/${selected.id}`).then(() => {
          this.$emit(
            "toast",
            "danger",
            "Deletion",
            "Color: " + selected.name + " Deleted"
          );
          let index = this.colors.findIndex(color => color.id === selected.id);
          this.colors.splice(index, 1);
        });
      });
    }
  },
  computed: {
    rows() {
      return this.colors.length;
    }
  },
  created() {
    this.read();
  }
};
</script>
