import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { connectionChanged } from '../actions/visitor';

import CarrierList from './CarrierList';

class ChangeConnection extends Component {

	componentDidMount() {
		if (navigator.connection) {
			// Commenting this for now.
			if (typeof navigator.connection.ontypechange == 'object') {
				navigator.connection.ontypechange = this.connectionDidChange.bind(this);
			} else if (typeof navigator.connection.onchange == 'object') {
				// TODO: (MERGE NOTE)
				// TODO: Remove this after front-end is done
				navigator.connection.onchange = this.connectionOnChange.bind(this);
			}
		}
	}

	componentDidUpdate(prevProps) {
		if ( (this.props.visitor.error !== prevProps.visitor.error) && (this.props.visitor.error) ) {
			this.props.connectionChanged(this.props.visitor.uid);
		}
	}

	// TODO: (MERGE NOTE)
	// TODO: Remove this after front-end is done
	connectionOnChange(e) {
		console.log('connection on change');
		this.props.connectionChanged(this.props.visitor.uid);
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
	}

	render() {
		if (this.props.carriers instanceof Array) {
			return (
				<div className="alert alert-primary">
					<select onChange={(e) => console.log('carrier change', e.target.value) }>
						<option>Select your mobile carrier</option>
						<CarrierList carriers={ this.props.carriers } />
					</select>
				</div>
			);
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

export default connect(mapStateToProps, { connectionChanged })(ChangeConnection);