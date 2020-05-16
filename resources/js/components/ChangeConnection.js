import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

import { ANDROID, IOS } from '../constants/devices';

import { connectionChanged, updateVisitorCarrier } from '../actions/visitor';

import CarrierList from './CarrierList';

import Asset from '../helpers/Asset'

class ChangeConnection extends Component {

	componentDidMount() {
		if (navigator.connection) {
			if (typeof navigator.connection.ontypechange == 'object') {
				navigator.connection.ontypechange = this.connectionDidChange.bind(this);
			}
		}
	}

	componentDidUpdate(prevProps) {
		if ( (this.props.visitor.error !== prevProps.visitor.error) && (this.props.visitor.error) ) {
			this.props.connectionChanged(this.props.visitor.uid, this.props.visitor.device);
		}
	}

	connectionDidChange(e) {
		if (navigator.connection.type == 'cellular') {
			this.props.connectionChanged(this.props.visitor.uid, this.props.visitor.device);
		}
	}

	connectionHandleChange() {
		this.props.connectionChanged(this.props.visitor.uid, this.props.visitor.device);
	}

	carrierHandleChange(e) {
		// TODO: Should show loading thing if it's updating the backend
		const selectedCarrier = e.target.value;

		this.props.updateVisitorCarrier(this.props.visitor.uid, selectedCarrier);
	}

	render() {
		if ( (this.props.carriers instanceof Array) && (this.props.carriers.length > 0) && (!this.props.visitor.carrier) ) {
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
		} else if ( (this.props.visitor.device == ANDROID) && (!this.props.visitor.connection) ) {
			return (
				<div className="alert alert-danger">Please switch to cellular connection</div>
			);
		} else if ( (this.props.visitor.device == IOS) && (!this.props.visitor.connection) ) {
			return (
				<div>
					<div className="alert alert-warning">You need to be on a cellular connection</div>
					<ol style={{ textAlign: 'left' }}>
						<li>If you're on wifi, please switch off wifi and connect to cellular</li>
						<li>If you're already on cellular or you've switched off wifi, click next</li>
					</ol>
					<div className='row'>
						<div className='col-12'>
							<img src={ Asset('/images/iphoneinstructions.gif') } style={{ marginBottom: '0.5rem', width: '35%' }} />
						</div>
					</div>
					<button className="btn btn-success" onClick={ this.connectionHandleChange.bind(this) } style={{ width: '80%', verticalAlign: 'middle' }}>Next <span style={{ verticalAlign: 'middle' }}><FontAwesomeIcon icon='angle-right' /></span></button>
				</div>
			);
		} else if (this.props.visitor.connection) {
			return <div className="alert alert-success">{ this.props.visitor.carrier }</div>;
		}
	}

}

const mapStateToProps = state => ({
	visitor: state.visitor,
	carriers: state.carriers
});

export default connect(mapStateToProps, { connectionChanged, updateVisitorCarrier })(ChangeConnection);
