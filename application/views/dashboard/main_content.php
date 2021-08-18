<!--    <pre>-->
<!--        {{contents}}-->
<!--    </pre>-->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        <h2>File Manager</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?php echo base_url(); ?>">Anasayfa</a>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox ">
                <div class="ibox-content">
                    <div class="file-manager">
                        <h5>Şuan ki klasör: </h5>
                        <span>{{ this.folder }}</span>
                        <div class="hr-line-dashed"></div>
                        <h5>Show:</h5>
                        <a href="#" class="file-control active">Ale</a>
                        <a href="#" class="file-control">Documents</a>
                        <a href="#" class="file-control">Audio</a>
                        <a href="#" class="file-control">Images</a>
                        <div class="hr-line-dashed"></div>
                        <h5>Bu klasördeki resimleri</h5>
                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#convert-modal"
                                @click="getImagesInFolder" v-if="!convertIsStart">Webp'ye Dönüştür
                        </button>
                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#convert-modal"
                                v-if="convertIsStart">Webp'ye Dönüştürme {{Math.round(convertPercentage)}}%
                        </button>
                        <div class="hr-line-dashed"></div>
                        <button class="btn btn-primary btn-block">Upload Files</button>
                        <div class="hr-line-dashed"></div>
                        <h5>Folders</h5>
                        <ul class="folder-list" style="padding: 0">
                            <li v-for="folder in getFolders"><a href="javascript:void(0)"
                                                                @click="getFilesInFolder(folder.path)"><i
                                            class="fa fa-folder"></i> {{folder.name}}</a></li>
                        </ul>
                        <h5 class="tag-title">Tags</h5>
                        <ul class="tag-list" style="padding: 0">
                            <li><a href="">Family</a></li>
                            <li><a href="">Work</a></li>
                            <li><a href="">Home</a></li>
                            <li><a href="">Children</a></li>
                            <li><a href="">Holidays</a></li>
                            <li><a href="">Music</a></li>
                            <li><a href="">Photography</a></li>
                            <li><a href="">Film</a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 animated fadeInRight">
            <div class="row">
                <div class="col-lg-12 lightBoxGallery">
                    <div class="alert alert-warning" v-if="getFiles.length == 0">Bu klasörde dosya
                        bulunmamaktadır.
                    </div>
                    <div v-for="content in getFiles" class="file-box">
                        <div v-if="content.type.includes('image')" class="file">
                            <a :href="content.path" data-gallery="" :data-download-url="content.path">
                                <span class="corner"></span>

                                <div class="image">
                                    <img alt="image" class="img-fluid" :src="content.path">
                                </div>
                                <div class="file-name">
                                    {{content.name}}
                                    <br/>
                                    <small>{{content.created_date}}</small>
                                </div>
                            </a>
                        </div>

                        <div v-if="content.type.includes('application') || content.type.includes('text')"
                             class="file">
                            <a :href="content.path">
                                <span class="corner"></span>

                                <div class="icon">
                                    <i class="fa fa-file"></i>
                                </div>
                                <div class="file-name">
                                    {{content.name}}
                                    <br/>
                                    <small>{{content.created_date}}</small>
                                </div>
                            </a>
                        </div>

                        <div v-if="content.type.includes('audio')" class="file">
                            <a :href="content.path">
                                <span class="corner"></span>

                                <div class="icon">
                                    <i class="fa fa-music"></i>
                                </div>
                                <div class="file-name">
                                    {{content.name}}
                                    <br/>
                                    <small>{{content.created_date}}</small>
                                </div>
                            </a>
                        </div>

                        <div v-if="content.type.includes('video')" class="file">
                            <a :href="content.path">
                                <span class="corner"></span>

                                <div class="icon">
                                    <i class="img-fluid fa fa-film"></i>
                                </div>
                                <div class="file-name">
                                    {{content.name}}
                                    <br/>
                                    <small>{{content.created_date}}</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="pagination">
                        <li class="footable-page-arrow disabled"><a data-page="first" href="javascript:void(0);" @click="currentPage = createPageLinks.first">«</a></li>
                        <li class="footable-page-arrow disabled"><a data-page="prev" href="javascript:void(0);" @click="currentPage = createPageLinks.prev">‹</a></li>
                        <li class="footable-page" v-for="page in createPageLinks.countOfPage" key="page" :class="{active: page.page == currentPage && page.text != '...' ? 'active' : ''}"><a data-page="1" href="javascript:void(0);" @click="currentPage = page.page">{{page.text}}</a></li>
                        <li class="footable-page-arrow"><a data-page="next" href="javascript:void(0);" @click="currentPage = createPageLinks.next">›</a></li>
                        <li class="footable-page-arrow"><a data-page="last" href="javascript:void(0);" @click="currentPage = createPageLinks.last">»</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <a target="_blank" class="download">İndir</a>
    <ol class="indicator"></ol>
</div>
<div class="modal inmodal fade" id="convert-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title">Listedeki resimler webp formatına dönüştürülecektir.</h5>
                <small class="font-bold">Dönüştürme işlemini başlatmak için "Başlat" butonuna tıklayınız.</small>
                <hr class="hr-line-dashed">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated progress-bar-danger"
                         :style="{width: convertPercentage+'%'}" role="progressbar"
                         :aria-valuenow="convertPercentage" aria-valuemin="0" aria-valuemax="100"></div>
                    <span style="position: absolute; left: 50%;"
                          :style="{color: convertPercentage < 50 ? '#000' : '#fff',}">{{Math.round(convertPercentage)}}%</span>
                </div>
                Toplam resim sayısı: {{images.length}} <br>
                Dönüştürülen resim sayısı: {{convertedFileCount}} <br>
                <div class="alert alert-warning" v-if="convertIsStart">Dönüştürme işlemi başladı. İşlem bitene kadar bu sayfayı yenilemeyin ya da kapatmayın!!!</div>
            </div>
            <div class="modal-body" style="height: 400px; overflow: auto;">
                <div class="image-item" v-if="failFiles.length != 0 && !convertIsStart" v-for="image in failFiles"
                     style="border-bottom: 2px solid #cecece; margin-bottom: 5px;">
                    <div class="stream-small" style="display: flex; justify-content: space-between;">
                        <span class="text-muted"> {{image.folder}} </span>
                        <span class="label label-danger" v-if="image.status == 'error'"> Hata</span>
                    </div>
                </div>
                <div class="image-item" v-for="image in images"
                     style="border-bottom: 2px solid #cecece; margin-bottom: 5px;">
                    <div class="stream-small" style="display: flex; justify-content: space-between;">
                        <span class="text-muted"> {{image.folder}} </span>
                        <span class="label label-success" v-if="image.status == 'complete'"> Tamamlandı</span>
                        <span class="label label-primary" v-if="image.status == 'uncomplete'"> Tamamlanmadı</span>
                        <span class="label label-danger" v-if="image.status == 'error'"> Hata</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary" @click="convertToWebp(images[0])" v-if="!convertIsStart">Başlat</button>
            </div>
        </div>
    </div>
</div>
