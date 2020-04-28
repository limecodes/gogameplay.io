import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { connectionChanged, updateVisitorCarrier } from '../actions/visitor';

import CarrierList from './CarrierList';

class ChangeConnection extends Component {

	componentDidMount() {
		if (navigator.connection) {
			if (typeof navigator.connection.ontypechange == 'object') {
				navigator.connection.ontypechange = this.connectionDidChange.bind(this);
			}
		}
	}

	componentDidUpdate(prevProps) {
		var self = this;

		if ( (this.props.visitor.error !== prevProps.visitor.error) && (this.props.visitor.error) ) {
			console.log('Attempting one more time');
			this.props.connectionChanged(this.props.visitor.uid);
		}

		if (this.props.visitor.error == prevProps.visitor.error) {
			console.log('Failed even after re-try');
		}
	}

	connectionDidChange(e) {
		if (navigator.connection.type == 'cellular') {
			this.props.connectionChanged(this.props.visitor.uid);
		}
	}

	connectionHandleChange() {
		this.props.connectionChanged(this.props.visitor.uid);
	}

	carrierHandleChange(e) {
		// TODO: Should show loading thing if it's updating the backend
		const selectedCarrier = e.target.value;

		this.props.updateVisitorCarrier(this.props.visitor.uid, selectedCarrier);
	}

	render() {
		if ( (this.props.carriers instanceof Array) && (!this.props.visitor.carrier) ) {
			return (
				<div className="alert alert-primary">
					<select onChange={ this.carrierHandleChange.bind(this) }>
						<option>Select your mobile carrier</option>
						<CarrierList carriers={ this.props.carriers } />
					</select>
				</div>
			);
		} else if (this.props.visitor.carrier) {
			return <div className="alert alert-success">{ this.props.visitor.carrier }</div>;
		} else if ( (this.props.visitor.device == 'android') && (!this.props.visitor.connection) ) {
			return (
				<div className="alert alert-danger">Please switch to cellular connection</div>
			);
		} else if ( (this.props.visitor.device == 'ios') && (!this.props.visitor.connection) ) {
			return (
				<div>
					<div className="alert alert-danger">Please switch to cellular connection</div>
					<button className="btn btn-success" onClick={ this.connectionHandleChange.bind(this) }>I've switched to cellular, Next ></button>
				</div>
			);
		} else if (this.props.visitor.connection) {
			return <div className="alert alert-success">{ this.props.visitor.carrier }</div>;
		}
	}

}

const mapStateToProps = state => ({
	visitor: state.visitor,
	carriers: state.carriers.carriers
});

export default connect(mapStateToProps, { connectionChanged, updateVisitorCarrier })(ChangeConnection);
