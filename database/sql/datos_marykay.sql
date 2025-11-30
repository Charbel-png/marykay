INSERT INTO `categorias` (`categoria_id`, `nombre`) VALUES
(1, 'Cuidado de la piel'),
(2, 'Maquillaje'),
(3, 'Fragancias'),
(4, 'Cuidado corporal'),
(5, 'Accesorios');

INSERT INTO `productos` (`producto_id`, `sku`, `nombre`, `descripcion`, `categoria_id`, `precio_lista`, `unidad`) VALUES
(1,  'TW-CLN-4EN1',        'Limpiadora 4 en 1 TimeWise',         'Limpiadora facial que limpia y refresca la piel.',                                    1, 360.00, 'pieza'),
(2,  'TW-DIA-FPS30',       'Crema de Día TimeWise FPS 30',       'Hidratante facial de día con protección solar FPS 30.',                               1, 420.00, 'pieza'),
(3,  'TW-MICRO-SET',       'Set Microexfoliante Facial',         'Dúo exfoliante para suavizar la textura de la piel.',                                 1, 720.00, 'set'),
(4,  'MK-BASE-MATTE-W130', 'Base Líquida TimeWise Matte W130',   'Base líquida de cobertura media con acabado mate.',                                   2, 420.00, 'pieza'),
(5,  'MK-LAB-MATE-RUBY',   'Labial Mate Ruby',                   'Labial en barra de acabado mate y larga duración.',                                   2, 220.00, 'pieza'),
(6,  'MK-DUO-RUB-ILUM',    'Dúo Rubor e Iluminador',             'Compacto con rubor e iluminador para mejillas.',                                     2, 330.00, 'pieza'),
(7,  'MK-FRAG-THINKYOU',   'Fragancia Thinking of You',          'Fragancia femenina con notas florales y frutales.',                                   3, 650.00, 'pieza'),
(8,  'MK-FRAG-MEN-DOM',    'Fragancia Men Domain',               'Fragancia masculina con notas amaderadas.',                                          3, 690.00, 'pieza'),
(9,  'MK-SH-CREMA-MANOS',  'Crema de Manos Satin Hands',         'Crema humectante para manos con aroma suave.',                                       4, 260.00, 'pieza'),
(10, 'MK-SPA-PIES-SET',    'Set Spa para Pies',                  'Set de exfoliante y crema para el cuidado de los pies.',                             4, 480.00, 'set'),
(11, 'MK-COSM-MED-ROSA',   'Cosmetiquera Rosa Mediana',          'Cosmetiquera mediana color rosa para productos Mary Kay.',                           5, 180.00, 'pieza'),
(12, 'MK-ORG-ACRILICO',    'Organizador Acrílico para Cosméticos','Organizador acrílico transparente para organizar cosméticos en tocador o escritorio.', 5, 350.00, 'pieza');

INSERT INTO `clientes` (`cliente_id`, `nombres`, `apellidos`, `email`, `telefono`, `fecha_reg`) VALUES
(1, 'Ana María', 'López Hernández',   'ana.lopez@example.com',    '4921234567', '2025-11-15 10:23:00'),
(2, 'Brenda',    'Torres García',     'brenda.torres@example.com','4922345678', '2025-11-16 15:40:00'),
(3, 'Carla',     'Gómez Ruiz',        'carla.gomez@example.com',  '4923456789', '2025-11-17 09:05:00'),
(4, 'Diana',     'Ramírez Flores',    'diana.ramirez@example.com','4924567890', '2025-11-18 18:12:00'),
(5, 'Erika',     'Sánchez Medina',    'erika.sanchez@example.com','4925678901', '2025-11-19 11:30:00');

INSERT INTO `direcciones` (`direccion_id`, `cliente_id`, `etiqueta`, `calle`,             `numero`,      `colonia`,      `ciudad`,     `estado`,     `cp`,    `pais`) VALUES
(1, 1, 'Casa',    'Calle Fresno',      '123',        'Centro',      'Zacatecas', 'Zacatecas', '98000', 'México'),
(2, 1, 'Trabajo', 'Av. Universidad',   '456 Int. 3', 'Lomas',       'Zacatecas', 'Zacatecas', '98010', 'México'),
(3, 2, 'Casa',    'Calle Roble',       '234',        'Colinas',     'Guadalupe', 'Zacatecas', '98610', 'México'),
(4, 3, 'Casa',    'Priv. Naranjo',     '12',         'Centro',      'Guadalupe', 'Zacatecas', '98600', 'México'),
(5, 3, 'Oficina', 'Av. García Salinas','789',        'Zona Centro', 'Zacatecas', 'Zacatecas', '98020', 'México'),
(6, 4, 'Casa',    'Calle Encino',      '321',        'Bosques',     'Guadalupe', 'Zacatecas', '98615', 'México'),
(7, 5, 'Casa',    'Calle Nogal',       '89',         'La Fe',       'Zacatecas', 'Zacatecas', '98030', 'México');

INSERT INTO `vendedores` (`vendedor_id`, `nombre`,         `email`,                         `telefono`,    `estatus`, `fecha_alta`,           `supervisor_id`) VALUES
(1, 'Lucía Herrera',      'lucia.herrera@example.com',      '4921112233', 'activo', '2025-10-01 09:00:00', NULL),
(2, 'Mariana Soto',       'mariana.soto@example.com',       '4922223344', 'activo', '2025-10-05 10:15:00', 1),
(3, 'Paola Medina',       'paola.medina@example.com',       '4923334455', 'activo', '2025-10-10 11:45:00', 1);

INSERT INTO `metodos_pago` (`metodo_pago_id`, `nombre`) VALUES
(1, 'Efectivo'),
(2, 'Tarjeta'),
(3, 'Transferencia'),
(4, 'PayPal');

INSERT INTO `estados_pedido` (`estado_id`, `nombre`) VALUES
(1, 'Creado'),
(2, 'Pagado'),
(3, 'Enviado'),
(4, 'Entregado'),
(5, 'Cancelado');

INSERT INTO `pedidos` (`pedido_id`, `cliente_id`, `vendedor_id`, `fecha`,                 `estado_id`, `direccion_envio_id`, `total`) VALUES
(1, 1, 2, '2025-11-20 14:30:00', 2, 1, 1287.60),  -- Ana, Mariana, Pagado
(2, 2, 2, '2025-11-21 16:10:00', 3, 3, 1055.60),  -- Brenda, Mariana, Enviado
(3, 3, 3, '2025-11-22 11:20:00', 1, 5, 1241.20),  -- Carla, Paola, Creado
(4, 5, 3, '2025-11-23 19:45:00', 4, 7, 1600.80);  -- Erika, Paola, Entregado

INSERT INTO `detalle_pedido` (`pedido_id`, `renglon`, `producto_id`, `cantidad`, `precio_unitario`, `descuento`, `iva_porcentaje`) VALUES
-- Pedido 1: Ana
(1, 1, 1, 2, 360.00,  0.00, 16.00),  -- 2× Limpiadora 4 en 1
(1, 2, 4, 1, 420.00, 30.00, 16.00),  -- 1× Base líquida con descuento

-- Pedido 2: Brenda
(2, 1, 7, 1, 650.00,  0.00, 16.00),  -- 1× Fragancia Thinking of You
(2, 2, 9, 1, 260.00,  0.00, 16.00),  -- 1× Crema de manos Satin Hands

-- Pedido 3: Carla
(3, 1, 3, 1, 720.00, 50.00, 16.00),  -- 1× Set Microexfoliante con descuento
(3, 2, 5, 1, 220.00,  0.00, 16.00),  -- 1× Labial Mate Ruby
(3, 3, 11,1, 180.00,  0.00, 16.00),  -- 1× Cosmetiquera Rosa Mediana

-- Pedido 4: Erika
(4, 1, 8, 2, 690.00,  0.00, 16.00);  -- 2× Fragancia Men Domain

