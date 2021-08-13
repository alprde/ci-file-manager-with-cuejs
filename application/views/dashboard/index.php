<!DOCTYPE html>
<html lang="tr">

<head>
    <?php $this->load->view("includes/head"); ?>
    <link href="<?php echo base_url("assets"); ?>/css/plugins/blueimp/css/blueimp-gallery.min.css" rel="stylesheet">
</head>

<body id="app">

<div id="wrapper">

    <?php $this->load->view("includes/sidebar"); ?>

    <div id="page-wrapper" class="gray-bg">
        <?php $this->load->view("includes/header"); ?>
        <?php $this->load->view("{$viewFolder}/main_content"); ?>
        <?php $this->load->view("includes/footer"); ?>
    </div>
</div>

<?php $this->load->view("includes/include_script"); ?>

<!-- blueimp gallery -->
<script src="<?php echo base_url("assets"); ?>/js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>

<script>
    const app = Vue.createApp({
        data(){
            return{
                contents: [],
                folder: "assets/images",
                folders: [],
                files: [],
                images: [],
                convertIsStart: false,
                convertedFileCount: 0,
                convertPercentage: 0,
                currentPage: 1,
                perPage: 12,
                countOfPage: 0,
                searchText: '',
            }
        },
        methods: {
            async getFilesInFolder(folder){
                console.log("getFilesInFolder çalıştı.")
                await axios.get("<?php echo base_url("Dashboard/getFilesInFolder/") ?>", {params: {folder: folder}}).then( response => {
                        // console.log(response.data)
                        this.contents = response.data.contents
                        this.folder = '';
                        this.folder = folder;
                    })

                console.log(folder)
            },
            async getImagesInFolder(){
                console.log("getFilesInFolder çalıştı.")
                await axios.get("<?php echo base_url("Dashboard/getImagesInFolder/") ?>", {params: {folder: this.folder}}).then( response => {
                        // console.log(response.data)
                        this.images = response.data.contents
                    })

            },
            convertToWebp(file){
                this.convertIsStart = true;
                let params = new FormData();
                params.append('file', JSON.stringify(file));
                axios.post("<?php echo base_url("Dashboard/convertToWebp/") ?>", params).then( response => {
                    console.log(response.data)
                    if(response.data.status == "complete"){
                        console.log("dönüştürme başarılı")
                        file.status = "complete";
                        this.convertedFileCount++;
                        if((this.convertedFileCount + 1) <= this.images.length){
                            this.convertToWebp(this.images[this.convertedFileCount]);
                        }else{
                            this.convertIsStart = false;
                        }
                        this.convertPercentage = (this.convertedFileCount / this.images.length) * 100;
                    }
                })
                console.log(file);
            },
        },
        computed: {
            getFolders(){
                this.folders = this.contents.filter(item => item.type == "directory")
                console.log("getFolders çalıştı.")
                return this.folders
            },
            getFiles(){
                console.log("getFiles çalıştı.");
                let filterFiles = this.contents.filter(item => item.type != "directory" && item.name.toLowerCase().includes(this.searchText.toLowerCase()));
                this.files = filterFiles.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage)
                this.countOfPage = Math.ceil(filterFiles.length / this.perPage)
                return this.files
            },
            createPageLinks(){
                let pageNumbers = [];

                for(let page = 1; page <= this.countOfPage; page++){
                    console.log(page)
                    if(Math.abs(this.currentPage - page) < 5){
                        pageNumbers.push({
                            text: page,
                            page: page
                        })
                    }
                    console.log(pageNumbers)
                }

                if(this.countOfPage - this.currentPage >= 5){
                    pageNumbers.push({
                        text: "...",
                        page: this.currentPage
                    })
                    pageNumbers.push({
                        text: this.countOfPage,
                        page: this.countOfPage
                    })
                }

                const links = {
                    first: 1,
                    prev: this.currentPage == 1 ? 1 : this.currentPage - 1,
                    countOfPage: pageNumbers,
                    next: this.currentPage == this.countOfPage ? this.countOfPage : this.currentPage + 1,
                    last: this.countOfPage
                }
                console.log(links);
                return links;
            }
        },
        async mounted(){
            console.log("mounted çalıştı.")
            await axios.get("<?php echo base_url("Dashboard/getFilesInFolder/") ?>", {params: {folder: this.folder}}).then( response => {
                    // console.log(response.data)
                    this.contents = response.data.contents
                })
        }
    }).mount("#app");
</script>

<script>
    $('#blueimp-gallery').on('slide', function (event, index, slide) {
        var url = $(this).data('gallery').list[index].getAttribute('data-download-url');
        $(this).find('.download').prop('href', url).prop('download', url);
    });
</script>
</body>

</html>
