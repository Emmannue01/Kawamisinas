const express = require('express');
const bodyParser = require('body-parser');
const { v4: uuidv4 } = require('uuid'); // Necesitas instalar uuid: npm install uuid
const cors = require('cors'); // Necesitas instalar cors: npm install cors
const app = express();

app.use(bodyParser.json());
app.use(cors()); // Permitir solicitudes desde cualquier origen

let orders = [];

app.post('/api/orders', (req, res) => {
    const order = { id: uuidv4(), ...req.body };
    orders.push(order);
    res.status(201).send(order);
});

app.delete('/api/orders/:id', (req, res) => {
    const id = req.params.id;
    orders = orders.filter(order => order.id !== id);
    res.status(204).send();
});

app.listen(3000, () => {
    console.log('Server running on port 3000');
});
