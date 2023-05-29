<section class="content-header" id="breadcrumb_forthistemplate_hack">
    <h1>&nbsp;</h1>
    <ol class="breadcrumb" style="float:left; left:10px;">
        <li class="breadcrumb-item"><a href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="breadcrumb-item active"><i class="fa fa-cogs"></i> Edit Badges</li>
    </ol>
</section>

<div class="col-md-8">
    <div class="box box-info">
        <div class="box-body">
            <div id="badgeRoot">
            </div>
        </div>
    </div>
</div>
{literal}
    <template id="badgeTpl">
        <div class="form-group" v-if="!showForm">
            <button @click="showAddForm" class="btn btn-success">Add Badge</button>
        </div>

        <div v-if="showForm">
            <div class="form-group">
                <p v-if="form.id>0">Note: Upload file only if you wish to change existing image.</p>
                <input type="file" @change="onFileChanged" class="form-control form-control-file"
                       accept=".png,.jpg,.gif,.svg,.jpeg"/>
            </div>
            <div class="form-group">
                <input type="text" v-model="form.name" class="form-control" placeholder="Badge Name"/>
            </div>
            <div class="form-group">
                <input type="text" v-model="form.description" class="form-control" placeholder="Description"/>
            </div>
            <div class="form-group">
                <input type="number" v-model="form.order" class="form-control" placeholder="Display order (default: 0)"/>
            </div>
            <div class="form-group">
                <button @click="saveForm" class="btn btn-primary">Save</button> &nbsp;&nbsp;&nbsp;&nbsp;
                <button @click="cancelForm" class="btn btn-secondary">Cancel</button>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Order</th>
                <th>Actions &nbsp; &nbsp;</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="badge in badges">
                <td><img :src="badge.badgeLocation" height="50" width="50"/></td>
                <td>{{badge.name}}</td>
                <td>{{badge.description}}</td>
                <td style="text-align: center">{{badge.order}}</td>
                <td>
                    <button class="btn btn-sm btn-primary" @click="editBadge(badge.id)">Edit</button>&nbsp;
                    <button class="btn btn-sm btn-danger" @click="deleteBadge(badge.id)">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </template>
{/literal}
<style type="text/css">
    .table td, .table th {
        vertical-align: middle !important;
    }
</style>
<script src="https://unpkg.com/vue@next"></script>
<script>

    const Counter = {
        data() {
            return {
                form: {
                    id: 0,
                    name: "",
                    description: "",
                    selectedFile: null
                },
                showForm: false,
                badges: [{
                    id: 0, badgeLocation: "", name: "Loading ...", description: "Loading..."
                }]
            }
        },
        template: '#badgeTpl',
        methods: {
            resetForm: function () {
                this.form = {
                    id: 0,
                    name: "",
                    description: "",
                    order: "",
                    selectedFile: null
                };
                this.showForm = false;
            },
            onFileChanged: function (event) {
                this.form.selectedFile = event.target.files[0];
            },
            refreshBadgeList: function () {
                let self = this;
                fetch(`{{NRURI}}badge/&token={{$token}}`)
                    .then(response => response.json())
                    .then(result => {
                        console.log('Success:', result);
                        let badgeBaseLocation = '{{$badgeBaseLocation}}';
                        let badgeList = result.data;
                        self.badges = [];
                        badgeList.map(function (badge) {
                            badge.badgeLocation = badgeBaseLocation + badge.badgeLocation;
                            console.log(badge);
                            self.badges.push(badge);
                        })
                    })
                    .catch(error => {
                        alert("an error occurred while fetching badges");
                        console.error('Error:', error);
                    });
            },
            deleteBadge: function (badgeId) {
                let result = confirm("Are you sure, you want to delete this?");
                if (!result) {
                    return;
                }
                const postParams = new FormData()
                postParams.append('badgeId', badgeId);
                postParams.append('token', `{{$token}}`);
                fetch('{{NRURI}}badge/delete/', {
                    method: 'POST',
                    body: postParams
                })
                    .then(response => response.json())
                    .then(result => {
                        console.log('Success:', result);
                        this.resetForm();
                        this.refreshBadgeList();
                    })
                    .catch(error => {
                        alert("an error occurred while deleting the badge");
                        console.error('Error:', error);
                    });

            },
            editBadge: function (badgeId) {
                this.resetForm();
                let self = this;
                this.badges.map((badge) => {
                    if (badge.id === badgeId) {
                        self.form = {
                            id: badge.id,
                            name: badge.name,
                            description: badge.description,
                            order: badge.order,
                            selectedFile: null
                        };
                        self.showForm = true
                    }
                });
            },
            saveForm: function () {
                console.log(this.form);
                const postParams = new FormData();

                if (this.form.id === 0 && this.form.selectedFile == null) { //for add new
                    alert("Badge Image file cannot be empty.")
                    return;
                }

                if (this.form.selectedFile != null) { //for edits
                    postParams.append('badgeImage', this.form.selectedFile, this.form.selectedFile.name);
                }
                postParams.append('badgeName', this.form.name);
                postParams.append('badgeDescription', this.form.description);
                postParams.append('badgeOrder', this.form.order);
                postParams.append('badgeId', this.form.id);
                postParams.append('token', `{{$token}}`);
                let edit = "";
                if (this.form.id > 0) {
                    edit = "edit"
                }
                fetch('{{NRURI}}badge/' + edit, {
                    method: 'POST',
                    body: postParams
                })
                    .then(response => response.json())
                    .then(result => {
                        console.log('Success:', result);
                        this.resetForm();
                        this.refreshBadgeList();
                    })
                    .catch(error => {
                        alert("an error occurred while saving the badge");
                        console.error('Error:', error);
                    });
            },
            showAddForm: function () {
                this.resetForm();
                this.showForm = true
            },
            cancelForm: function () {
                this.resetForm();
            }
        },
        mounted: function () {
            this.refreshBadgeList();
        }
    }

    Vue.createApp(Counter).mount('#badgeRoot')
</script>
