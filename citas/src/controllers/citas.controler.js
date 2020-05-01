const citasCtrl = {};

const Cita = require('../models/Cita');

citasCtrl.renderCitaForm = (req, res) => {
    res.render('citas/new-cita');
};

citasCtrl.createNewCita = async (req, res) => {
    const { id_number, name, lastname, birth, city, neighborhood, phone } = req.body;
    const citaid = await Cita.findOne({id_number: id_number});
    const newCita = new Cita({id_number, name, lastname, birth, city, neighborhood, phone});
    newCita.user = req.user.id;
    if (citaid) { // Para evitar crear cita duplicada
        req.flash('error_msg', 'Cita already Exists.');
        return res.redirect('/citas');
    }
    await newCita.save();
    req.flash('success_msg', 'Cita Added Successfully!');
    res.redirect('/citas');
};

citasCtrl.renderCitas = async (req, res) => {
    // Sólo muestra las citas creadas por el usuario que inició sesión y las organiza por fecha de creación descendente.
    const citas = await Cita.find({user: req.user.id}).sort({createdAt: 'desc'});
    res.render('citas/all-citas', { citas });
};

citasCtrl.renderEditForm = async (req, res) => {
    const cita = await Cita.findById(req.params.id);
    if (cita.user != req.user.id) { // Para evitar que otro usuario edite una cita que no le pertenece.
        req.flash('error_msg', 'Not Authorized.');
        return res.redirect('/citas');
    }
    res.render('citas/edit-cita', { cita });
};

citasCtrl.updateCita = async (req, res) => {
    const { name, lastname, birth, city, neighborhood, phone } = req.body;
    const cita = await Cita.findById(req.params.id);
    if (cita.user != req.user.id) { // Para evitar que otro usuario edite una cita que no le pertenece.
        req.flash('error_msg', 'Not Authorized.');
        return res.redirect('/citas');
    }
    await Cita.findByIdAndUpdate(req.params.id, { name, lastname, birth, city, neighborhood, phone });
    req.flash('success_msg', 'Cita Updated Successfully!');
    res.redirect('/citas');
};

citasCtrl.deleteCita = async (req, res) => {
    const cita = await Cita.findById(req.params.id);
    if (cita.user != req.user.id) { // Para evitar que otro usuario elimine una cita que no le pertenece.
        req.flash('error_msg', 'Not Authorized.');
        return res.redirect('/citas');
    }
    await Cita.findByIdAndDelete(req.params.id);
    req.flash('success_msg', 'Cita Deleted Successfully!');
    res.redirect('/citas');
};

module.exports = citasCtrl;

