import React, { Component } from 'react';
import ReactDom from 'react-dom';

import { connect } from 'react-redux';

import { validateCarrier } from '../actions/validation';

import ChangeConnection from './ChangeConnection';

class CarrierCard extends Component {

	handleCarrierValidate() {
		this.props.validateCarrier();
	}

	render() {
		const ConfirmButton = () => {
			if (this.props.visitor.carrier) {
				return (
					<div className="card-footer" style={{ textAlign: 'right' }}>
						<button className="btn btn-success" onClick={ this.handleCarrierValidate.bind(this) }>Confirm</button>
					</div>
				);
			} else {
				return (
					<div className="card-footer" style={{ textAlign: 'right' }}>
						<button className="btn btn-secondary">Confirm</button>
					</div>
				);
			}
		}

		return (
			<div className="card">
				<div className="card-header">Carrier</div>
				<div className="card-body">
					<ChangeConnection />
				</div>
				<ConfirmButton />
			</div>
		);
	}
}

const mapStateToProps = state => ({
	visitor: state.visitor
})

export default connect(mapStateToProps, { validateCarrier })(CarrierCard);