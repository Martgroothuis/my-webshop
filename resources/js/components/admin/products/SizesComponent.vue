<template>
  <div v-if="this.productVersion.id">
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
        <div class="m-1 ml-3" v-if="this.productVersion.id">
          <h5>Product Version: {{this.productVersion.id}}</h5>
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
                <b-button variant="primary" v-b-modal.newSize>New Waistsize</b-button>
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

      <b-modal id="deleteSizes" ref="deleteSizes" title="Delete Size" hide-footer>
        <h5>Are you shure to delete {{selection.length}} color(s)</h5>
        <h6>You are about to delete:</h6>
        <form @submit.prevent="destroy()">
          <ul v-for="selected in selection" v-bind:key="selected.id">
            <li>{{selected.id}}</li>
          </ul>

          <b-button block type="submit" variant="danger">Delete!</b-button>
        </form>
      </b-modal>

      <b-modal id="newSize" ref="newSize" title="New Size" hide-footer>
        <form @submit.prevent="create()">
          <b-form-group label="waist_size_id:" label-for="waist_size_id">
            <b-form-input
              v-model="form.waist_size_id"
              type="number"
              name="waist_size_id"
              required
              placeholder="Enter waist_size_id"
            ></b-form-input>
          </b-form-group>
          <b-form-group label="length_size_id:" label-for="length_size_id">
            <b-form-input
              v-model="form.length_size_id"
              type="number"
              name="length_size_id"
              required
              placeholder="Enter length_size_id"
            ></b-form-input>
          </b-form-group>
          <b-form-group label="stock:" label-for="stock">
            <b-form-input
              v-model="form.stock"
              type="number"
              name="stock"
              required
              placeholder="Enter stock"
            ></b-form-input>
          </b-form-group>
          <b-button block type="submit" variant="primary">Submit</b-button>
        </form>
      </b-modal>
    </div>
    <b-table
      ref="selectableSizesTable"
      selectable
      selected-variant="active"
      select-mode="range"
      @row-selected="onRowSelected"
      small
      hover
      responsive="sm"
      :items="sizes"
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
    <b-modal id="editSize" ref="editSize" title="Edit Size" hide-footer>
      <form @submit.prevent="update()">
        <b-form-group label="waist_size_id:" label-for="waist_size_id">
          <b-form-input
            v-model="form.waist_size_id"
            type="number"
            name="waist_size_id"
            required
            placeholder="Enter waist_size_id"
          ></b-form-input>
        </b-form-group>
        <b-form-group label="length_size_id:" label-for="length_size_id">
          <b-form-input
            v-model="form.length_size_id"
            type="number"
            name="length_size_id"
            required
            placeholder="Enter length_size_id"
          ></b-form-input>
        </b-form-group>
        <b-form-group label="stock:" label-for="stock">
          <b-form-input
            v-model="form.stock"
            type="number"
            name="stock"
            required
            placeholder="Enter stock"
          ></b-form-input>
        </b-form-group>
        <b-button block type="warning" variant="primary">Update Size</b-button>
      </form>
    </b-modal>
  </div>
  <div v-else>
    <h3>Select a product version first.</h3>
  </div>
</template>

<script>
function Size({
  id,
  product_version_id,
  waist_size_id,
  length_size_id,
  stock
}) {
  this.id = id;
  this.product_version_id = product_version_id;
  this.waist_size_id = waist_size_id;
  this.length_size_id = length_size_id;
  this.stock = stock;
}

export default {
  props: ["productVersion"],
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
      sizes: [],
      productVersion: this.productVersion,
      fields: [
        { key: "id", sortable: true },
        { key: "product_version_id", sortable: true },
        { key: "waist_size_id", sortable: true },
        { key: "length_size_id", sortable: true },
        { key: "stock", sortable: true }
      ]
    };
  },
  methods: {
    selectAllRows() {
      this.$refs.selectableSizesTable.selectAllRows();
    },
    clearSelected() {
      this.$refs.selectableSizesTable.clearSelected();
    },
    onRowSelected(items) {
      this.selection = items;
    },
    create() {
      this.$refs["newSize"].hide();
      window.axios
        .post("/api/sizes", {
          _token: this.csrf,
          product_version_id: this.productVersion.id,
          waist_size_id: this.form.waist_size_id,
          length_size_id: this.form.length_size_id,
          stock: this.form.stock
        })
        .then(({ data }) => {
          this.$emit("toast", "success", "Create", "Size: Created");
          this.sizes.push(new Size(data));
        });
    },
    read() {
      this.mute = true;
      console.log(this.productVersion.id);
      window.axios
        .get(`/api/sizes/${this.productVersion.id}`)
        .then(({ data }) => {
          this.sizes = [];
          data.forEach(size => {
            this.sizes.push(new Size(size));
          });
          this.mute = false;
        });
    },
    edit() {
      this.form = this.selection[0];
      this.$refs["editSize"].show();
    },
    update() {
      var form = this.form;
      window.axios
        .put(`/api/sizes/${this.form.id}`, {
          _token: this.csrf,
          product_version_id: this.form.product_version_id,
          waist_size_id: this.form.waist_size_id,
          length_size_id: this.form.length_size_id,
          stock: this.form.stock
        })
        .then(({ data }) => {
          console.log(data);
          this.$refs["editSize"].hide();
          this.$emit(
            "toast",
            "warning",
            "Update",
            "Size: " + form.id + " Updated"
          );
          // this.colors.find(color => color.id === form.id).name = form.name;
          // this.colors.find(color => color.id === form.id).waterless =
          //   form.waterless;
          // this.colors.find(color => color.id === form.id).rise = form.rise;
          this.form = [];
        });
    },
    del() {
      this.$refs["deleteSizes"].show();
    },
    destroy() {
      this.selection.forEach(selected => {
        this.$refs["deleteSizes"].hide();
        window.axios.delete(`/api/sizes/${selected.id}`).then(() => {
          this.$emit(
            "toast",
            "danger",
            "Deletion",
            "Size: " + selected.id + " Deleted"
          );
          let index = this.sizes.findIndex(size => size.id === selected.id);
          this.sizes.splice(index, 1);
        });
      });
    }
  },
  watch: {
    productVersion: function() {
      this.read();
    }
  },
  computed: {
    rows() {
      return this.sizes.length;
    }
  }
};
</script>
