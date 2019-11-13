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
          <h4>Lengthsize</h4>
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
                <b-button variant="primary" v-b-modal.newLengthsize>New Lengthsize</b-button>
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

      <b-modal id="deleteLengthsizes" ref="deleteLengthsizes" title="Delete Lengthsize" hide-footer>
        <h5>Are you shure to delete {{selection.length}} color(s)</h5>
        <h6>You are about to delete:</h6>
        <form @submit.prevent="destroy()">
          <ul v-for="selected in selection" v-bind:key="selected.id">
            <li>{{selected.size}}</li>
          </ul>

          <b-button block type="submit" variant="danger">Delete!</b-button>
        </form>
      </b-modal>

      <b-modal id="newLengthsize" ref="newLengthsize" title="New Lengthsize" hide-footer>
        <form @submit.prevent="create()">
          <b-form-group label="Lengthsize:" label-for="lengthsize">
            <b-form-input
              v-model="form.size"
              type="number"
              name="lengthsize"
              required
              placeholder="Enter Lengthsize"
            ></b-form-input>
          </b-form-group>
          <b-button block type="submit" variant="primary">Submit</b-button>
        </form>
      </b-modal>
    </div>
    <b-table
      ref="selectableLengthsizesTable"
      selectable
      selected-variant="active"
      select-mode="range"
      @row-selected="onRowSelected"
      small
      hover
      responsive="sm"
      :items="lengthsizes"
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
    <b-modal id="editLengthsize" ref="editLengthsize" title="Edit Lengthsize" hide-footer>
      <form @submit.prevent="update()">
        <b-form-group id="input-group-price" label="Lengthsize:" label-for="lengthsize">
          <b-form-input
            v-model="form.size"
            type="number"
            name="lengthsize"
            required
            placeholder="Enter lengthsize"
          ></b-form-input>
        </b-form-group>
        <b-button block type="warning" variant="primary">Update Lengthsize</b-button>
      </form>
    </b-modal>
  </div>
</template>

<script>
function Lengthsize({ id, size }) {
  this.id = id;
  this.size = size;
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
      lengthsizes: [],
      fields: [{ key: "id", sortable: true }, { key: "size", sortable: true }]
    };
  },
  methods: {
    selectAllRows() {
      this.$refs.selectableLengthsizesTable.selectAllRows();
    },
    clearSelected() {
      this.$refs.selectableLengthsizesTable.clearSelected();
    },
    onRowSelected(items) {
      this.selection = items;
    },
    create() {
      this.$refs["newLengthsize"].hide();
      window.axios
        .post("/api/lengthsizes", {
          _token: this.csrf,
          size: this.form.size
        })
        .then(({ data }) => {
          this.$emit(
            "toast",
            "success",
            "Create",
            "Lengthsize: " + this.size + " Created"
          );

          this.lengthsizes.push(new Lengthsize(data));
        });
    },
    read() {
      this.mute = true;
      window.axios.get("/api/lengthsizes").then(({ data }) => {
        this.lengthsizes = [];
        data.forEach(lengthsize => {
          this.lengthsizes.push(new Lengthsize(lengthsize));
        });
        this.mute = false;
      });
    },
    edit() {
      this.form = this.selection[0];
      console.log(this.form);
      this.$refs["editLengthsize"].show();
    },
    update() {
      var form = this.form;
      window.axios
        .put(`/api/lengthsizes/${this.form.id}`, {
          size: form.size
        })
        .then(({ data }) => {
          console.log(data);
          this.$refs["editLengthsize"].hide();
          this.$emit(
            "toast",
            "warning",
            "Update",
            "Lengthsize: " + form.size + " Updated"
          );
          // this.colors.find(color => color.id === form.id).name = form.name;
          // this.colors.find(color => color.id === form.id).waterless =
          //   form.waterless;
          // this.colors.find(color => color.id === form.id).rise = form.rise;
          this.form = [];
        });
    },
    del() {
      this.$refs["deleteLengthsizes"].show();
    },
    destroy() {
      this.selection.forEach(selected => {
        this.$refs["deleteLengthsizes"].hide();
        window.axios.delete(`/api/lengthsizes/${selected.id}`).then(() => {
          this.$emit(
            "toast",
            "danger",
            "Deletion",
            "Size: " + selected.size + " Deleted"
          );
          let index = this.lengthsizes.findIndex(
            lengthsize => lengthsize.id === selected.id
          );
          this.lengthsizes.splice(index, 1);
        });
      });
    }
  },
  computed: {
    rows() {
      return this.lengthsizes.length;
    }
  },
  created() {
    this.read();
  }
};
</script>
