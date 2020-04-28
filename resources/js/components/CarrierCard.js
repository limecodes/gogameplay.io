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
					<div className="card-footer">
						<button className="btn btn-success" style={{ width: '100%' }} onClick={ this.handleCarrierValidate.bind(this) }>Next ></button>
					</div>
				);
			} else {
				return (
					<div className="card-footer">
						<button className="btn btn-danger" style={{ width: '100%' }} >Next ></button>
						<small>You need to be on a cellular connection to verify carrier</small>
					</div>
				);
			}
		}

		return (
			<div className="card" style={{ marginTop: '1rem' }}>
				<div className="card-header" style={{ textAlign: 'center' }}>2. Verify Your Cellular Carrier</div>
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