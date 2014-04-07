<?php 

session_start();
if(!empty($_SESSION["usuarioReal"]))
{
	$_SESSION["usuario"] = $_SESSION["usuarioReal"];
	unset($_SESSION["usuarioReal"]);
}
$session = $_SESSION;
unset($_SESSION["error"]);
session_write_close();

?>

<?php 
require_once('phpfn/cndb.php');
$con = conection();
$query = $con->prepare('SELECT * FROM `usuario` where idusuario = ?');
$query->bindValue(1,$session["usuario"], PDO::PARAM_INT);
$query->execute();
$usuario = $query->fetch(PDO::FETCH_OBJ);
require_once('templates/headgeneral.php');
?>

<div class="businessInfoWrap termsAndConditions">	
	
<h1 class="businessInfoHeading">Términos de uso</h1>

<p>POR FAVOR LEA ESTAS CONDICIONES DE USO ("ACUERDO" O "Condiciones de Uso") ANTES DE UTILIZAR EL SITIO WEB Y SERVICIOS OFRECIDOS POR TuMall. Este Acuerdo establece los términos jurídicamente vinculante Y CONDICIONES PARA EL USO DEL SITIO WEB EN <a href="http://www.tumall.do">www.tumall.do</a> (el "Sitio") Y TODOS LOS SERVICIOS PRESTADOS POR TuMall EN EL SITIO.</p>

<p>Al utilizar el Sitio en cualquier forma, incluyendo pero no limitado a, visitar o navegar por el Sitio, usted (el "Usuario" o "usted") acepta que quedará vinculado por el presente Acuerdo, incluidos los términos adicionales, condiciones y políticas mencionados en este documento y / o disponible por hipervínculo. El presente Convenio se aplica a todos los usuarios del Sitio, incluyendo sin limitación los usuarios que son los proveedores, los clientes, los comerciantes, los contribuyentes de contenido, información y otros materiales o servicios en el Sitio.</p>

<p>Si usted tiene alguna pregunta, por favor, consulte la sección de ayuda del sitio.</p>

<p>1. TuMall es un lugar</p>

<p>TuMall actúa como un lugar para que los usuarios que cumplan con las políticas de TuMall puedan ofrecer, vender y comprar ciertos bienes con arreglos a un formato de precio fijo. TuMall no está directamente involucrado en las transacciones entre compradores y vendedores. Como resultado, TuMall no tiene ningún control sobre la calidad, la seguridad, la moralidad o legalidad de cualquier aspecto de las opciones, la veracidad o exactitud de los anuncios, la capacidad de los vendedores para vender artículos o la capacidad de los compradores para pagar los artículos. TuMall no cuenta con usuarios de preselección o el contenido o la información proporcionada por los usuarios. TuMall no puede garantizar que un comprador o vendedor en realidad pueda completar una transacción.</p>

<p>En consecuencia, TuMall no transfiere la propiedad legal de los artículos del vendedor al comprador.</p>

<p>TuMall no puede garantizar la verdadera identidad, edad y nacionalidad de un usuario. TuMall alienta a comunicarse directamente con los potenciales socios de transacción a través de las herramientas disponibles en el Sitio. Puede que también desee considerar el uso de un servicio o de los servicios que proporcionan la verificación de usuario adicional a custodia de terceros.</p>

<p>Usted acepta que TuMall es un lugar y como tal no se hace responsable de ningún contenido, por ejemplo, datos, texto, información, nombres de usuario, gráficos, imágenes, fotografías, perfiles, audio, video, artículos y enlaces enviados por usted, otros usuarios o grupos ajenos a TuMall. Utilice el servicio de TuMall bajo su propio riesgo.</p>

<p>2. Elegibilidad de Membresía</p>

<p>Edad: los servicios de TuMall están disponibles sólo para, y sólo podrán ser utilizados por los individuos que tienen 18 años o más que puedan celebrar contratos legalmente vinculantes conforme al derecho aplicable. Usted manifiesta y garantiza que tiene al menos 18 años de edad y que toda la información de registro que presente es correcta y verdadera. TuMall puede, a su sola discreción, negarse a ofrecer el acceso o uso del Sitio a cualquier persona o entidad y cambiar sus criterios de selección en cualquier momento. Esta disposición es nulo donde esté prohibido por la ley y el derecho a acceder al Sitio es revocado en esas jurisdicciones.</p>

<p>Las personas menores de 18 años deben utilizar en todo momento los servicios de TuMall sólo junto con y bajo la supervisión de un padre o tutor legal que tenga por lo menos 18 años de edad. De este modo todos los casos, el adulto es el usuario y es responsable por cualquier y todas las actividades.</p>

<p>Cumplimiento: Usted acepta cumplir con todas las leyes locales sobre conducta y contenido aceptable. Usted es responsable de todos los impuestos aplicables. Además, debe cumplir con las políticas de TuMall como se indica en el Acuerdo y los documentos de política de TuMall que se enumeran a continuación (si es aplicable a sus actividades dentro o uso del Sitio), así como todas las demás reglas de operación, políticas y procedimientos que pueden ser publicados de vez en cuando en el Sitio por TuMall, cada uno de los cuales se incorporan aquí como referencia y cada uno de los cuales puede ser actualizado por TuMall de vez en cuando sin previo aviso:</p>

<p>3. Listado y Venta</p>

<p>Listado Descripción: Todos los anuncios en TuMall deben estar a la venta. Al enumerar un artículo en el sitio, usted garantiza que usted y todos los aspectos del artículo cumple con las políticas publicadas de TuMall. Usted también garantiza que puede vender legalmente el artículo. Debe describir con precisión su artículo y todos los términos de la venta en su tienda de TuMall. Sus anuncios sólo podrán incluir descripciones de texto, gráficos, imágenes y otros contenidos relevantes a la venta de ese material. </p>

<p>Todos los artículos deben estar inscritos en una categoría adecuada con etiquetas apropiadas. Cada lista debe ser precisa y completa al describir el artículo / artículos a la venta en esa lista. Si en "el stock" cantidad es más de uno, todos los elementos de esa lista deben ser idénticos. Cada artículo único debe tener su propia lista.</p>

<p>Venta Encuadernación: Los vendedores son responsables de enumerar con precisión sus elementos, y los compradores son responsables de leer la descripción de artículos antes de hacer una compra. Todas las ventas son vinculantes. El vendedor está obligado a enviar la orden o no completar la transacción con el comprador de una manera rápida, a menos que exista una circunstancia excepcional, como por ejemplo: (a) el comprador no cumple con los términos del listado del vendedor (como forma de pago), o (b) el vendedor no puede autenticar la identidad del comprador. El comprador está obligado a entregar el pago apropiado para los artículos comprados, a menos que exista una circunstancia excepcional.</p>

<p>Fee Prevención: El precio indicado en la descripción de cada listado de los artículos debe ser una representación exacta de la venta. Los vendedores pueden cobrar razonablemente el envío y acarreo para cubrir los costes de embalaje y envío de los artículos. Los vendedores no podrán cobrar honorarios excesivos de envío o de otra manera evitar cargos. No se puede alterar el precio del producto después de una venta con el fin de evitar las comisiones por transacciones de TuMall, tergiversar la ubicación del elemento, o utilizar la cuenta de otro usuario sin permiso.</p>

<p>4. Prohibidas, artículos y actividades cuestionables e Infringir</p>

<p>Usted es el único responsable de su conducta y actividades en y con respecto a TuMall y cualquier y todos los datos, textos, informaciones, nombres de usuario, gráficos, imágenes, fotografías, perfiles, audio, video, artículos y enlaces (en conjunto, "Contenido") que usted envíe, publiquen y muestre en TuMall.</p>

<p>Actividades restringidas: el contenido y el uso de TuMall no podrá:</p>

<p>Ser falsa, inexacta o engañosa.</p>

<p>Ser fraudulenta o involucrar la venta de productos ilegales, falsificados o robados.</p>

<p>Infringir los derechos de autor, patentes, marcas, secretos comerciales de cualquier tercero u otros derechos de propiedad o propiedad intelectual o derechos de publicidad o privacidad (véase también, de TuMall Derecho de Autor y Propiedad Intelectual Política )</p>

<p>Violar el presente Acuerdo, las Directrices de TuMall, cualquier política sitio o directrices de la comunidad, o cualquier ley, estatuto, ordenanza o regulación (incluyendo, pero no limitado a, las que rigen el control de las exportaciones, la protección al consumidor, competencia desleal, anti-discriminación o la publicidad engañosa)</p>

<p>Ser difamatorio, de calumnioso comercio, de amenaza ilegal, de acoso ilegal, intimidar o suplantar a cualquier persona (incluido el personal de TuMall u otros usuarios), o declarar falsamente o tergiversar su afiliación con cualquier persona, a través de, por ejemplo, el uso de la dirección de correo electrónico similar, apodos, o la creación de falsa cuenta (s) o cualquier otro método o dispositivo</p>

<p>Interferir con el negocio o la tienda de un vendedor</p>

<p>Tomar cualquier acción que pueda socavar los comentarios en línea o comentarios </p>

<p>Ser obsceno o que contengan pornografía infantil</p>

<p>Contener o transmitir cualquier código de naturaleza destructiva que puedan dañar, interferir perjudicialmente, interceptar subrepticiamente o expropiar cualquier sistema, dato o información personal</p>

<p>Imágenes anfitrión no forma parte de una lista</p>

<p>Modificar, adaptar o hackear TuMall o modificar otro sitio web para implicar falsamente que está asociado con TuMall; y Parecen representar una responsabilidad legal para TuMall </p>

<p>Enlace directo o indirecto, de referencia o contener la descripción de los bienes o servicios que están prohibidos en virtud del presente Acuerdo, la Política de Privacidad , Directrices de TuMall, u otros documentos de política como publicada en TuMall.</p>

<p>Además, es posible que no incluya ningún elemento en TuMall (o consumar una transacción que se inició el uso de servicio de TuMall) que, mediante el pago de TuMall de la tasa de cotización o el valor final, podría causar TuMall para violar cualquier ley, estatuto, ordenanza o Reglamento, o que viole los Términos de uso.</p>

<p>5. Contenido</p>

<p>Licencia: TuMall no reclama derechos de propiedad en su Contenido. Usted concede una licencia únicamente para permitir a TuMall utilizar cualquier información o contenido que usted suministre a TuMall, para que TuMall no este violando los derechos que pueda tener ese contenido. Usted concede a TuMall una licencia no exclusiva, mundial, perpetua, irrevocable, libre de regalías, sublicenciable (a través de múltiples niveles) derecho a ejercer los derechos de autor, publicidad y derechos de base de datos (pero no otros derechos) que tiene en el contenido, en cualquier medio de comunicación ahora se conoce o no se conoce en la actualidad, con respecto a su contenido. Usted acepta que TuMall para almacenar o volver a formatear el contenido en TuMall y mostrar su contenido en TuMall en modo alguno como TuMall elige. TuMall sólo usará la información personal de acuerdo con la Política de Privacidad de TuMall.</p>

<p>Como parte de la transacción, puede obtener información personal, incluyendo dirección de correo electrónico y la información de envío, de otro usuario TuMall. Sin obtener el permiso previo del otro usuario, esta información personal sólo será utilizada para la transacción o para las comunicaciones relacionadas con TuMall. TuMall no le ha concedido una licencia de uso de la información de los mensajes comerciales no solicitados. Sin perjuicio de lo anterior, sin el consentimiento expreso del usuario, no tiene licencia para agregar cualquier usuario TuMall a su correo electrónico o lista de correo físico. Para obtener más información, consulte las Políticas de Privacidad de TuMall.</p>

<p>Contenido Vuelva anuncio: Al publicar Contenido en TuMall, es posible que un sitio web externo o un tercero para volver a publicar dicho Contenido. Usted acepta mantener indemne TuMall para cualquier controversia relativa a este uso. Si opta por mostrar una imagen propia de TuMall alojada en otro sitio Web, la imagen debe proporcionar un enlace a su página de listado en TuMall.</p>

<p>Presentación de Ideas: TuMall considera cualquier sugerencia no solicitada, ideas, propuestas o cualquier otro material que se le presenten por los usuarios a través del Sitio o de otra manera (que no sea el contenido y los elementos tangibles vendidos en el sitio por los usuarios) (colectivamente, el "Material") para como no confidencial y no propietaria, y TuMall no será responsable por la divulgación o uso de dicho material. Si, a petición de TuMall, cualquier miembro envía material para mejorar el sitio (por ejemplo a través de los Foros o atención al cliente), TuMall también considerará que el Material sea no confidencial y no propietario y TuMall no será responsable del uso o divulgación del material. Cualquier comunicación por usted a TuMall está sujeto a este Acuerdo. Usted otorga y se compromete a conceder TuMall, bajo todos sus derechos sobre el material, una licencia no exclusiva perpetua, irrevocable y totalmente desembolsado derecho en todo el mundo, libre de regalías, sublicenciable y transferible y licencia para incorporar, utilizar, publicar y explotar dicho material para cualquier fin, comercial o de otro tipo, incluyendo pero no limitado a la incorporación en la API, documentación, o cualquier producto o servicio, sin compensación o de contabilidad para usted y sin ulterior recurso por parte de usted.</p>

<p>6. Control de la Información</p>

<p>TuMall no controla el contenido proporcionado por los usuarios que se ponen a disposición en TuMall. Usted puede encontrar un poco de contenido ofensivo, dañino, inexacto o engañoso. También existen riesgos de tratar con personas menores de edad o personas que actúan bajo una identidad falsa.</p>

<p>Además, puede también haber riesgos relacionados con el comercio internacional y los extranjeros. Mediante el uso de TuMall, usted se compromete a aceptar estos riesgos y que TuMall (y los oficiales de TuMall, directores, agentes, subsidiarias, empresas conjuntas y empleados) no se hacen responsables de cualquier y todos los actos u omisiones de los usuarios en TuMall. Actúe con prudencia y sentido común, y la práctica de compra y ventas seguras al utilizar TuMall.</p>

<p>Otros recursos: TuMall no es responsable de la disponibilidad de los sitios web o recursos vinculados o referenciados en el Sitio externos. TuMall no avala ni se hace responsable de ningún contenido, publicidad, productos u otros materiales que no estén disponibles en dichos sitios o recursos. Usted acepta que TuMall no será responsable o estará obligado, directa o indirectamente, por cualquier daño o pérdida causada o supuestamente causada por o en conexión con el uso o la credibilidad en cualquier Contenido, bienes o servicios disponibles en TuMall, a través de cualquier sitio web o recursos.</p>

<p>7. Reuniones</p>

<p>Los usuarios pueden organizar y asistir a reuniones virtuales en línea o reuniones en persona ("Reuniones") con una o más personas. Los usuarios son los únicos responsables de las interacciones con los demás. Los usuarios deben cumplir con las políticas de TuMall y reconocen y se comprometen a cumplir con las leyes de la ciudad, el condado y el país en el que tiene lugar la reunión. Requisitos adicionales de elegibilidad para un grupo o reunión pueden ser establecidos por la persona de contacto del grupo. TuMall no está involucrado con grupos de usuarios generados, los requisitos de los grupos, o por la Asamblea.</p>

<p>TuMall no supervisa ni controla las reuniones, iniciadas por el usuario reuniones en línea o fuera de línea, o las interacciones entre y entre los usuarios y otras personas o empresas. Los usuarios son los únicos responsables de las interacciones con los demás. </p>

<p>Los usuarios entienden que TuMall no en cualquier pantalla de sus usuarios así. Todos los usuarios se comprometen a actuar con cautela y buen juicio en todas las interacciones con los demás, sobre todo si se satisfacen fuera de línea o en persona.</p>

<p>Los grupos o cuotas de los usuarios: Algunos grupos generados por los usuarios en TuMall pueden requerir o solicitar que los usuarios paguen tarifas con el fin de ser un miembro del grupo, participar en una promoción, o participar en las reuniones. TuMall no está involucrado en las transacciones, los pagos o su procesamiento. EL PAGO NO SE VA A TuMall, este dinero es independiente de cualquier cuota o cargos calculados POR TuMall, y TuMall NO TIENE CONTROL SOBRE EL DINERO, LOS GRUPOS generados por el usuario, PROMOCIONES, LAS REUNIONES O EL LÍDER DEL GRUPO EN RELACIÓN CON SU USO. </p>

<p>USTED RECONOCE Y ACEPTA QUE TuMall no es PARTICIPE SIN NIGUN PARTIDO EN CUALQUIER PAGO, TRANSACCIÓN O INTERACCIÓN ENTRE o entre grupos generados por el usuario, USUARIOS TuMall y / o de terceros (conjuntamente una "Transacción de Terceros"), Y QUE USTED ASUME TODO RIESGO EN RELACIÓN CON SUS OPERACIONES DE TERCEROS. Los usuarios deben utilizar el sentido común y tener cuidado al decidir si se aporte dinero.</p>

<p>8. Resolución de controversias y de la versión</p>

<p>Disputas con TuMall: En el caso de una disputa entre usted y TuMall, por favor póngase en contacto con TuMall. Cualquier disputa que surja de o esté relacionada con la materia objeto de este Acuerdo deberán resolverse mediante arbitraje en la República Dominicana, Santo Domingo, utilizando el lenguaje Español de conformidad con el Reglamento de Arbitraje y Procedimientos de Arbitraje, que estén en vigor, por un árbitro comercial con amplia experiencia en la resolución de la propiedad intelectual y disputas de contratos comerciales, que se elegirán de la lista correspondiente de los atascos de árbitros de conformidad con las normas y procedimientos de arbitraje. La parte ganadora en cualquier arbitraje u otro procedimiento que surja de este Acuerdo tendrá derecho a recibir el reembolso de sus gastos razonables (incluyendo honorarios razonables de abogados, honorarios de testigos expertos y otros gastos) incurridos en relación con la misma. La sentencia sobre el laudo dictado por lo se puede introducir en un tribunal que tenga jurisdicción o aplicación se pueden hacer a dicho tribunal para la aceptación judicial del laudo y una orden de ejecución, según el caso puede ser. No obstante lo anterior, cada parte tiene derecho a ejercitar la acción ante un tribunal de la jurisdicción apropiada para el desagravio por mandato judicial u otro en espera de una decisión final del árbitro. Para todos los efectos del presente Acuerdo, las partes consienten a la jurisdicción exclusiva y lugar en los Tribunales de la República. El uso del Sitio Web no está autorizado en jurisdicción alguna que no haga efectivas todas las disposiciones del Acuerdo, incluyendo, sin limitación, en este apartado. Usted y TuMall acepta que cualquier causa de acción que surja de o esté relacionada con el Sitio (incluyendo, pero no limitado a, los servicios prestados o puestos a disposición en el mismo) o el presente Contrato deberá iniciarse dentro de un (1) año después de la causa de la acción, de lo contrario, dicha causa de acción caducará de forma definitiva.</p>

<p>Las disputas con usuarios o terceros: En el caso de una disputa entre usted y otro usuario o un tercero, TuMall le invita a ponerse en contacto con el usuario o un tercero para resolver el litigio de forma amistosa. </p>

<p>Si el comprador y el vendedor son incapaces de resolver una disputa resultante de una operación que se produce en TuMall, pueden optar a participar en el sistema de casos de TuMall, a fin de resolver la disputa. Los casos se extendieron para su revisión y resolución de TuMall. TuMall ofrece su proceso de resolución de disputas para el beneficio de los usuarios. TuMall lo hace a la sola discreción de TuMall, y TuMall no tiene obligación de resolver las controversias entre usuarios o entre usuarios y terceros. En la medida en que TuMall intenta resolver una disputa, TuMall lo hará de buena fe, basadas exclusivamente en las políticas de TuMall, no haciendo juicios sobre asuntos legales o reclamaciones.</p>

<p>También puede informar sobre los conflictos de usuario a usuario a su policía local, director general de correos, o una mediación certificada o entidad de arbitraje, según corresponda. </p>

<p>Usted libera TuMall (y los oficiales de TuMall, directores, agentes, subsidiarias, empresas conjuntas y empleados) de cualquier y todas las reclamaciones, demandas y daños (reales o resultantes) de cualquier tipo y naturaleza, conocidos y desconocidos, sospechados e insospechados, revelados y no revelados, que surja de o relacionados de alguna manera con las disputas con uno o más usuarios, o un interlocutor externo. </p>

<p>9. Propiedad Intelectual de TuMall</p>

<p>TuMall, y otros gráficos de TuMall logotipos, diseños, encabezados de página, iconos de botones, scripts y nombres de servicios son marcas registradas, marcas comerciales o imágenes comerciales de TuMall. Marcas de TuMall y la imagen comercial no pueden ser utilizados, incluyendo como parte de marcas y / o como parte de los nombres de dominio o direcciones de correo electrónico, en relación con cualquier producto o servicio de ninguna manera que pueda causar confusión.</p>

<p>10. Acceso e interferencia</p>

<p>TuMall puede contener encabezados de exclusión de robots que contienen normas internas para el uso del software. Mucha de la información en TuMall se actualiza en una base en tiempo real y es propietaria o tiene licencia de TuMall por los usuarios de TuMall o terceros. Usted acepta que no utilizará ningún robot, spider, scraper u otro medio automatizado para acceder TuMall para cualquier fin, excepto en la medida permitida por y en cumplimiento de TuMall Términos de Uso API o cualquier otro sin previa y expresa autorización por escrito de TuMall. Además, usted acepta que no podrá:</p>

<p>Tomar cualquier acción que imponga o pueda imponer, a la sola discreción de TuMall, una carga irrazonable o desproporcionadamente grande sobre la infraestructura de TuMall</p>

<p>Copiar, reproducir, modificar, crear trabajos derivados, distribuir o mostrar públicamente el Contenido del usuario (a excepción de su contenido) u otros usos permitidos según lo establecido en las Directrices de TuMall desde el sitio, salvo en la medida expresamente permitida por y en cumplimiento de TuMall API Términos de Uso o cualquier otro sin la previa y expresa autorización de TuMall y la tercera parte correspondiente escrito, según corresponda</p>

<p>Interferir o intentar interferir con el funcionamiento adecuado del Sitio o de cualquier actividad realizada en el Sitio</p>

<p>Omitir los encabezados de exclusión de robots de TuMall u otras medidas TuMall puede utilizar para prevenir o limitar el acceso a TuMall</p>

<p>11. Incumplimiento</p>

<p>Sin perjuicio de cualquier otro recurso, TuMall puede, sin previo aviso y sin devolver ningún honorario, retrasar o retirar inmediatamente contenido, advertir a la comunidad de las acciones de un usuario de TuMall, emitir una advertencia a un usuario, suspender temporalmente a un usuario, de manera temporal o indefinidamente suspender a un usuario de privilegios de la cuenta, cancelar la cuenta de un usuario, prohibir el acceso al Sitio, y tomar medidas técnicas y legales para mantener a un usuario de la web y se niegan a prestar servicios a un usuario si alguno de los siguientes casos:</p>

<p>Sospechosos de TuMall (por la información, la investigación, la convicción, la liquidación, el seguro o la investigación de custodia, o de otra manera) el usuario ha incumplido este Acuerdo, la Política de Privacidad, Directrices de TuMall , u otros documentos de política y directrices comunitarias incorporadas en este documento; TuMall es incapaz de verificar o autenticar a cualquiera de su información personal o contenido, o TuMall cree que un usuario está actuando de manera incompatible con la letra o el espíritu de las políticas de TuMall, se ha involucrado en actividades impropias o fraudulentas en relación con TuMall o las acciones puede causar responsabilidad jurídica o pérdida financiera para TuMall de usuarios o para TuMall.</p>

<p>12. Intimidad</p>

<p>Salvo lo dispuesto en la Política de privacidad de TuMall, TuMall no venderá ni revelará su información personal (tal como se define en la Política de Privacidad) a terceros sin su consentimiento expreso. Tiendas de TuMall y procesos de contenido en los equipos ubicados en los Estados Unidos que están protegidos por la seguridad física y tecnológica.</p>

<p>13. No Garantía</p>

<p>TuMall, SUBSIDIARIAS TuMall, OFICIALES, DIRECTORES, EMPLEADOS Y PROVEEDORES DE TuMall PRESTACIÓN DE SITIO Y LOS SERVICIOS WEB DE TuMall "TAL CUAL" Y SIN NINGUNA GARANTÍA O CONDICIÓN EXPRESA, IMPLÍCITA O LEGAL. TuMall, SUBSIDIARIAS TuMall, OFICIALES, DIRECTORES, EMPLEADOS Y PROVEEDORES DE TuMall ESPECÍFICAMENTE RECHAZAN CUALQUIER GARANTÍA DE TÍTULO, COMERCIALIZACIÓN, RENDIMIENTO, APTITUD PARA UN PROPÓSITO PARTICULAR Y NO INFRACCIÓN. ADEMÁS, NINGÚN CONSEJO O INFORMACIÓN (ORAL O ESCRITA) OBTENIDA POR USTED DE TuMall OTORGAN NINGUNA GARANTÍA. ALGUNOS ESTADOS NO PERMITEN LA EXCLUSIÓN DE GARANTÍAS IMPLÍCITAS, POR LO QUE LAS ANTERIORES RENUNCIA NO SE APLIQUE EN SU CASO. ESTA GARANTÍA LE OTORGA DERECHOS LEGALES ESPECÍFICOS Y USTED TAMBIÉN PUEDE TENER OTROS DERECHOS LEGALES QUE PUEDEN VARIAR DE UN ESTADO A OTRO.</p>

<p>14. Límite de Responsabilidad</p>

<p>EN NINGÚN CASO, TuMall, Y (según corresponda) SUBSIDIARIAS TuMall, OFICIALES, DIRECTORES, EMPLEADOS O PROVEEDORES DE TuMall SERÁN RESPONSABLES POR DAÑOS DE CUALQUIER TIPO, YA SEAN DIRECTOS, INDIRECTOS, GENERALES, ESPECIALES, compensatorios, consecuentes, Y / O INCIDENTAL, RESULTANTE DE O RELACIONADAS CON LA REALIZACIÓN DE USTED O CUALQUIER OTRA PERSONA EN RELACIÓN CON EL USO DE ESTE SITIO, LOS SERVICIOS DE TuMall, O ESTE ACUERDO, INCLUYENDO SIN LIMITACIÓN, PÉRDIDA DE BENEFICIOS, DAÑOS CORPORALES, angustia emocional, O CUALQUIER DAÑO ESPECIAL, INCIDENTAL O CONSECUENTE. </p>

<p>15. Indemnidad </p>

<p>USTED ACEPTA INDEMNIZAR Y TuMall y (según corresponda) PADRES TuMall , subsidiarias, afiliados, funcionarios, directores, agentes y empleados, de cualquier reclamo o demanda, incluyendo honorarios razonables de abogados, hechos por cualquier tercero debido a, O DERIVADOS DEL INCUMPLIMIENTO DE ESTE ACUERDO O LOS DOCUMENTOS que incorpora por referencia, o su VIOLACIÓN DE CUALQUIER LEY O LOS DERECHOS DE TERCEROS.</p>

<p>16. No Garantía</p>

<p>TuMall no garantiza el acceso continuo e ininterrumpido al sitio, y el funcionamiento del Sitio puede verse afectado por diversos factores fuera del control de TuMall.</p>

<p>17. Cumplimiento Legal, Impuestos</p>

<p>Usted deberá cumplir con todas las leyes nacionales e internacionales, estatutos, ordenanzas y reglamentos relativos a su uso del Sitio y cualquier servicio de TuMall y, en su caso, de su establecimiento, adquisición, solicitud de ofertas de compra y venta de materiales. Además, usted será responsable de pagar todos los impuestos aplicables a las compras o ventas de artículos que usted realice en el Sitio (excluidos los impuestos sobre la renta neta de TuMall).</p>

<p>18. Divisibilidad</p>

<p>Si alguna disposición de este Acuerdo se considera inaplicable, dicha disposición será modificada para reflejar la intención de las partes. Todas las restantes disposiciones del presente Acuerdo permanecerá en pleno vigor y efecto.</p>

<p>19. No Agencia</p>

<p>Usted y TuMall son contratistas independientes, y ninguna agencia, sociedad, empresa conjunta, empleado-empleador o de la relación franquiciador-franquiciado se pretende generar o creado por el presente Acuerdo.</p>

<p>20. Servicio TuMall</p>

<p>TuMall Se reserva el derecho de modificar o terminar el servicio TuMall por cualquier motivo, sin previo aviso, en cualquier momento. TuMall Se reserva el derecho de modificar estos Términos de Uso u otras políticas del sitio en cualquier momento, así que por favor revise las políticas de frecuencia. Si se hace un cambio material notificaremos aquí, por correo electrónico, por medio de un aviso en nuestra página de inicio, o en otros lugares que TuMall considere apropiado. Lo que constituye un "cambio material" se determinará a discreción de TuMall, de buena fe y con sentido común y buen juicio razonable.</p>

<p>21. Elección de la ley</p>

<p>El presente Acuerdo a todos los efectos interpretarse y entenderse con y por las leyes de la República Dominicana.</p>

<p>22. Supervivencia</p>

<p>Secciones 3 (Tasas y servicios, tarifas y terminación), 6 (contenido, Licencia), 7 (Control de la Información), 8 (Reuniones, Grupos o tarifas a los usuarios), 9 (Resolución de Disputas y prensa), 10 (de la Propiedad Intelectual de TuMall ) , 11 (Acceso e interferencia), 12 (Violación), 13 (Privacidad), 14 (Sin Garantía), 15 (Límite de Responsabilidad), 16 (Indemnización), 17 (Sin Garantía), 19 (Separación), 20 (No Agencia ), 22 (Elección de la ley) sobrevivirán a cualquier terminación o expiración de este Acuerdo.</p>

<p>23. Avisos </p>

<p>Salvo que se indique expresamente lo contrario, todo aviso deberá ser enviado por correo electrónico a TuMall, A dirección de correo <a href="mailto:info@tumall.do">info@tumall.com</a> (ya sea durante el proceso de registro o cuando cambia su dirección electrónica). La notificación se considerará dado 24 horas después de correo electrónico se envía, a menos que se notifique la parte remitente que la dirección de correo electrónico no es válida. Alternativamente, TuMall puede proporcionarle una notificación por correo certificado, con franqueo pagado y acuse de recibo solicitado, a la dirección indicada a TuMall. En tal caso, la notificación se entenderá realizada tres días después de la fecha de envío.</p>

<p>Por problemas con la propiedad intelectual, por favor proporcione la notificación según lo especificado en TuMall de Derecho de Autor y Política de Propiedad Intelectual.</p>

<p>24. Revelaciones</p>

<p>La continuación de servicios son ofrecidos por TuMall, ubicado en la Ciudad de Santo Domingo, Distrito Nacional, Capital de la República Dominicana, Si usted es un residente de La República Dominicana, es posible que esta misma información enviada por correo electrónico, enviando una carta a la dirección anterior con su dirección de correo electrónico y una solicitud de esta información.</p>

	
</div>


<?php 
	require_once('templates/foo.php');
	
?>