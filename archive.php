<?php get_header(); ?>
  <div class="page-content">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-9">
          <main class="results-list">
            <?php 
              if(have_posts()){
                while(have_posts()):{
                  the_post();
                  if(get_post_type(get_the_ID()) == 'activities'){
                    get_template_part('partials/activity', 'archive-loop');
                  }
                  else{
                    get_template_part('partials/impact', 'archive-loop');
                  }
                }
              }
              else{
                echo '<p>Sorry, nothing was found.</p>';
              }
            ?>
<div class="media">
  <div class="media-left">
    <span class="result-type-icon impact"></span>
  </div>
  <div class="media-body">
    <h3 class="media-heading">Introduction of exotic species</h3>
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <div class="sector-icon">
          <img src="images/icon-agriculture.png" class="img-circle img-responsive" alt="Agriculture Sector" style="background-color:#8cc63f;" />
        </div>
      </div>
      <div class="col-sm-8 col-md-9">
        <div class="number-activities">
          <a href="#"><span>6</span>Activities</a>
        </div>
      </div>
    </div>
  </div>
</div>
            <div class="media">
              <div class="media-left">
                <span class="result-type-icon activity"></span>
              </div>
              <div class="media-body">
                <h3 class="media-heading">Training and workshops to build capacity with respect to related activities such as planting of tree crops, climate smart agriculture, resilient agro-forestry methods, agricultural intensification, and access to credit.</h3>
                <div class="row">
                  <div class="col-sm-4 col-md-3">
                    <div class="sector-icon">
                      <img src="images/icon-health.png" class="img-circle img-responsive" alt="Health Sector" style="background-color:#c1272d;" />
                    </div>
                  </div>
                  <div class="col-sm-8 col-md-9">
                    <div class="factor-grid">
                      <div class="grid-item">
                        <a href="#" class="factor-name">Forest</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Deforestation</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Poor Extension Policies</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Vet Pharma</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Road</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Pest Management</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="media">
              <div class="media-left">
                <span class="result-type-icon activity"></span>
              </div>
              <div class="media-body">
                <h3 class="media-heading">Training and workshops to build capacity with respect to related activities such as planting of tree crops, climate smart
                  agriculture, resilient agro-forestry methods, agricultural intensification, and access to credit.</h3>
                <div class="row">
                  <div class="col-sm-4 col-md-3">
                    <div class="sector-icon">
                      <img src="images/icon-health.png" class="img-circle img-responsive" alt="Health Sector" style="background-color:#c1272d;"
                      />
                    </div>
                  </div>
                  <div class="col-sm-8 col-md-9">
                    <div class="factor-grid">
                      <div class="grid-item">
                        <a href="#" class="factor-name">Forest</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Deforestation</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Poor Extension Policies</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Vet Pharma</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Road</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Pest Management</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="media">
              <div class="media-left">
                <span class="result-type-icon activity"></span>
              </div>
              <div class="media-body">
                <h3 class="media-heading">Training and workshops to build capacity with respect to related activities such as planting of tree crops, climate smart
                  agriculture, resilient agro-forestry methods, agricultural intensification, and access to credit.</h3>
                <div class="row">
                  <div class="col-sm-4 col-md-3">
                    <div class="sector-icon">
                      <img src="images/icon-health.png" class="img-circle img-responsive" alt="Health Sector" style="background-color:#c1272d;"
                      />
                    </div>
                  </div>
                  <div class="col-sm-8 col-md-9">
                    <div class="factor-grid">
                      <div class="grid-item">
                        <a href="#" class="factor-name">Forest</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Deforestation</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Poor Extension Policies</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Vet Pharma</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Road</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Pest Management</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="media">
              <div class="media-left">
                <span class="result-type-icon activity"></span>
              </div>
              <div class="media-body">
                <h3 class="media-heading">Training and workshops to build capacity with respect to related activities such as planting of tree crops, climate smart
                  agriculture, resilient agro-forestry methods, agricultural intensification, and access to credit.</h3>
                <div class="row">
                  <div class="col-sm-4 col-md-3">
                    <div class="sector-icon">
                      <img src="images/icon-health.png" class="img-circle img-responsive" alt="Health Sector" style="background-color:#c1272d;"
                      />
                    </div>
                  </div>
                  <div class="col-sm-8 col-md-9">
                    <div class="factor-grid">
                      <div class="grid-item">
                        <a href="#" class="factor-name">Forest</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Deforestation</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Poor Extension Policies</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Vet Pharma</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Road</a>
                      </div>
                      <div class="grid-item">
                        <a href="#" class="factor-name">Pest Management</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </main>
        </div>
        <div class="col-sm-4 col-md-3">
          <aside class="results-sidebar hidden-xs">
            <div class="sidebar-section">
              <form action="/" method="get">
                <div class="form-group">
                  <label for="search-field" class="sr-only">Search</label>
                  <div class="input-group">
                    <input type="text" id="search-field" name="s" class="form-control" />
                    <div class="input-group-btn">
                      <input type="submit" class="btn-search" value="" />
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="sidebar-section">
              <h4>REPORT (6)</h4>
              <a href="#">View Report</a>
            </div>
            <div class="sidebar-section">
              <h4>BROWSE IMPACTS BY SECTOR</h4>
              <ul>
                <li><a href="#">Agriculture - General</a></li>
                <li><a href="#">Agriculture - Irrigation</a></li>
                <li><a href="#">Agriculture - Livestock</a></li>
                <li><a href="#">Environmental - General</a></li>
              </ul>
            </div>
          </aside>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>