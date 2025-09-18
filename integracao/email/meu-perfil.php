<div class="container-meu-perfil">
    <div class="meu-perfil-descricao">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
            <g>
                <path class="st0" d="M466.85,484.63H45.15V323.9l210.86-42.69l210.85,42.69V484.63z M76.91,452.86h358.19V349.88l-179.09-36.26
                    l-179.1,36.26V452.86z M256.01,241.06c-58.92,0-106.84-47.93-106.84-106.84S197.09,27.37,256.01,27.37S362.85,75.3,362.85,134.22
                    S314.92,241.06,256.01,241.06z M256.01,59.14c-41.4,0-75.08,33.68-75.08,75.08s33.68,75.08,75.08,75.08s75.08-33.68,75.08-75.08
                    S297.41,59.14,256.01,59.14z"/>
            </g>
        </svg>

        <div>
            <h1>Meu Perfil</h1>
			<a href="index.php/meu-perfil">
				<svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
					<g>
						<g>
							<path class="st0" d="M346.23,437.77H73.59V165.13h176.64l45.6-45.6H51.03c-12.48,0-22.56,10.08-22.56,22.56v318.24
								c0,12.48,10.08,22.56,22.56,22.56h318.24c12.48,0,22.56-10.08,22.56-22.56v-244.8l-45.6,45.6L346.23,437.77z"/>
							<path class="st0" d="M461.43,27.37H333.27c-12.48,0-22.56,10.08-22.56,22.56c0,12.48,10.08,22.56,22.56,22.56h72.96L194.07,285.13
								c-9.12,9.12-9.12,23.04,0,32.16c4.32,4.32,10.08,6.72,15.84,6.72s11.52-2.4,15.84-6.72l212.64-212.16v72.96
								c0,12.48,10.08,22.56,22.56,22.56c12.48,0,22.56-10.08,22.56-22.56V49.94C483.99,37.46,473.91,27.38,461.43,27.37L461.43,27.37z"
								/>
						</g>
					</g>
				</svg>
			</a>
        </div>
        
        <p>Conclusão do meu perfil</p>

<?php
	$result_perfil = mysqli_query($con,"SELECT description, image, date_format(str_to_date(user_alteracao, '%Y-%m-%d %H:%i:%s'), '%d/%m/%Y %H:%i:%s') AS data FROM `g1fda_k2_users` WHERE userID = '".$user_id."';") or die(mysqli_error($con));
	$row_perfil = mysqli_fetch_array( $result_perfil );

	$media_perfil = 0;
	$perc_perfil = 0;
	$cont_perfil = 0;
	if($row_perfil["description"]){$cont_perfil++;}
	if($row_perfil["image"]){$cont_perfil++;}
		
	$result_experiencias = mysqli_query($con,"SELECT COUNT(experiencia_id) AS total FROM `sis_experiencias` WHERE experiencia_usuario = '".$user_id."'") or die(mysqli_error($con));
	$row_experiencias = mysqli_fetch_array( $result_experiencias );
	if($row_experiencias["total"]){$cont_perfil++;}
	
	$result_cursos = mysqli_query($con,"SELECT COUNT(curso_id) AS total FROM `sis_cursos` WHERE curso_usuario = '".$user_id."'") or die(mysqli_error($con));
	$row_cursos = mysqli_fetch_array( $result_cursos );
	if($row_cursos["total"]){$cont_perfil++;}
	
	$media_perfil = $cont_perfil / 4;
	$perc_perfil = $media_perfil * 100;
?>
		<?php if($row_perfil["data"]): ?>
			<p class="small">Última atualizaçao em <?php echo $row_perfil["data"]; ?></p>
		<?php endif; ?>
    </div>
    <div class="progress">
        <div class="circle">
            <div class="circle-progress">
                <div id="progress_perfil">
                    <div class="descricao-progress">
                        <span class="percent"><?php echo $perc_perfil; ?>%</span><br>
                        <span>Completo</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/site/sistemas/dashboard/progress/dist/circle-progress.js"></script>

    <script>
        jQuery.circleProgress.defaults.arcCoef = 0.5; // range: 0..1
        jQuery.circleProgress.defaults.startAngle = 0.5 * Math.PI;

        jQuery.circleProgress.defaults.drawArc = function(v) {
            var ctx = this.ctx,
                r = this.radius,
                t = this.getThickness(),
                c = this.arcCoef,
                a = this.startAngle + (1 - c) * Math.PI;
            
            v = Math.max(0, Math.min(1, v));

            ctx.save();
            ctx.beginPath();

            if (!this.reverse) {
                ctx.arc(r, r, r - t / 2, a, a + 2 * c * Math.PI * v);
            } else {
                ctx.arc(r, r, r - t / 2, a + 2 * c * Math.PI, a + 2 * c * (1 - v) * Math.PI, a);
            }

            ctx.lineWidth = t;
            ctx.lineCap = this.lineCap;
            ctx.strokeStyle = this.arcFill;
            ctx.stroke();
            ctx.restore();
        };

        jQuery.circleProgress.defaults.drawEmptyArc = function(v) {
            var ctx = this.ctx,
                r = this.radius,
                t = this.getThickness(),
                c = this.arcCoef,
                a = this.startAngle + (1 - c) * Math.PI;

            v = Math.max(0, Math.min(1, v));
            
            if (v < 1) {
                ctx.save();
                ctx.beginPath();

                if (v <= 0) {
                    ctx.arc(r, r, r - t / 2, a, a + 2 * c * Math.PI);
                } else {
                    if (!this.reverse) {
                        ctx.arc(r, r, r - t / 2, a + 2 * c * Math.PI * v, a + 2 * c * Math.PI);
                    } else {
                        ctx.arc(r, r, r - t / 2, a, a + 2 * c * (1 - v) * Math.PI);
                    }
                }

                ctx.lineWidth = t;
                ctx.strokeStyle = this.emptyFill;
                ctx.stroke();
                ctx.restore();
            }
        };

        jQuery('#progress_perfil').circleProgress({
            arcCoef: 0.7,
            value: "<?php echo $media_perfil; ?>",
            thickness: 5,
            size: 137,
            animation: { duration: 3500, easing: "circleProgressEasing" },
            fill: { gradient: ['#47ab37', '#0997b7']}
        });
    </script>
</div>