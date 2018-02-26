ERROR - 2018-02-24 18:13:47 --> Severity: Warning --> pg_query(): Query failed: ERROR:  cannot extract elements from an object /var/www/html/smiledu_store/system/database/drivers/postgre/postgre_driver.php 242
ERROR - 2018-02-24 18:13:48 --> Query error: ERROR:  cannot extract elements from an object - Invalid query: SELECT p.id_grupo,
                       p.id_persona,
                       p.nom_pers,
                       p.ape_pate_pers,
                       p.ape_mate_pers,
                       p.correo_pers,
                       p.tipo_doc,
                       p.num_doc,
                       p.usuario,
                       p.correo_pers,
                       CASE WHEN p.foto_pers IS NOT NULL THEN CONCAT('https://s3.amazonaws.com/smiledustore/uploads/images/foto_perfil/', p.foto_pers)
                            ELSE 'https://s3.amazonaws.com/smiledustore/public/img/profile/nouser.svg'
                       END AS foto_pers,
                       p.flg_activo,
                       g.dominio,
                       p.uuid,
                       c.flg_activo AS cont_activ,
                       c.id_contrato,
                       CASE WHEN c.json_estados = '{}' 
                            THEN ''::TEXT
                            ELSE (SELECT jsonb_array_elements(json_estados)->>'primer_pago'  
                                    FROM contrato ct 
                                   WHERE ct.id_grupo = c.id_grupo)
                       END primer_pago
                 FROM  persona p, grupo_educativo g,contrato c
                WHERE  (LOWER(p.usuario) = LOWER('juan.cabezas') OR LOWER(correo_pers) = LOWER('juan.cabezas'))
                  AND  p.id_grupo = g.id_grupo
                  AND  p.id_grupo = c.id_grupo
                  AND  p.clave = 'Cabezas123'
                  AND  CASE WHEN (SELECT COUNT(1) FROM contrato co WHERE co.id_grupo = p.id_grupo) > 1 
                            THEN c.flg_activo = 1
                            ELSE 1 = 1 
                       END
                LIMIT  1
