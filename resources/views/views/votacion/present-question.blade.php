<div>
    <style>
        .list-group-item.active::before {
            content: none;
            /* Elimina el pseudo-elemento ::before */
        }
        .list-group-item.light {
            border-width: 1px; /* Ajusta el grosor del borde */
            border-color:blueviolet;
        }
        .list-group-item.light.first {
            border-top: solid 2px;
            border-color:blueviolet;
        }
        .list-group-item.dark {
            border-width: 1px; /* Ajusta el grosor del borde */
            border-color:white;
            border-right: 0px
        }


    </style>
    <div class="row mt-3 justify-content-center">
        <div class="col-12 align-items-center ">
                <div class="card ">
                    <div class="card-header d-flex align-items-center   ">

                        <div class="col-11  text-center ms-2">
                            <h1 class="mb-0 text-uppercase super-large-text " id="title" >{{$question->title}}</h1>
                        </div>
                        <div class="col-1 mx-2">
                            <button type="submit" class="btn btn-primary" wire:click='voting' >
                                <i class="bi bi-clipboard-check-fill fs-3"  ></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body d-flex">

                        <ul class="list-group ">
                            <li class="list-group-item  bg-primary text-light dark">
                                <h1 class="mb-0 super-large-text ">A</h1>
                            </li>
                            <li class="list-group-item  bg-primary text-light  dark">
                                <h1 class="mb-0 super-large-text ">B</h1>
                            </li>
                            <li class="list-group-item  bg-primary text-light  dark">
                                <h1 class="mb-0 super-large-text ">C</h1>
                            </li>
                            <li class="list-group-item  bg-primary text-light dark">
                                <h1 class="mb-0 super-large-text ">D</h1>
                            </li>
                            <li class="list-group-item  bg-primary text-light dark">
                                <h1 class="mb-0 super-large-text ">E</h1>
                            </li>
                            <li class="list-group-item  bg-primary text-light dark">
                                <h1 class="mb-0 super-large-text ">F</h1>
                            </li>

                        </ul>
                        <ul class="list-group w-100 ">
                            <li class="list-group-item light first">
                                <h1 class="mb-0 text-uppercase super-large-text">{{$question->optionA}} &nbsp;</h1>
                            </li>
                            <li class="list-group-item light">
                                <h1 class="mb-0 text-uppercase super-large-text">{{$question->optionB}} &nbsp;</h1>
                            </li>
                            <li class="list-group-item light">
                                <h1 class="mb-0 text-uppercase super-large-text">{{$question->optionC}} &nbsp;</h1>
                            </li>
                            <li class="list-group-item light">
                                <h1 class="mb-0 text-uppercase super-large-text">{{$question->optionD}} &nbsp;</h1>
                            </li>
                            <li class="list-group-item light">
                                <h1 class="mb-0 text-uppercase super-large-text">{{$question->optionE}} &nbsp;</h1>
                            </li>
                            <li class="list-group-item light">
                                <h1 class="mb-0 text-uppercase super-large-text">{{$question->optionF}} &nbsp;</h1>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
    document.addEventListener('DOMContentLoaded', function() {
      const textElement = document.getElementById('title');

      if (textElement.textContent.length > 15) {
        textElement.classList.remove('super-large-text');
        textElement.classList.add('medium-large-text');
      }
      if (textElement.textContent.length > 34) {
        textElement.classList.remove('super-large-text');
        textElement.classList.add('normal-large-text');
      }
    });
  </script>
