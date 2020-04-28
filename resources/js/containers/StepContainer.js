import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import PlatformCard from '../components/PlatformCard';
import CarrierCard from '../components/CarrierCard';
import Instructions from '../components/Instructions';
import Searching from '../components/Searching';

class StepContainer extends Component {

	render() {
		if ( (this.props.validation.platform) && (this.props.validation.carrier) ) {
			return (
				<div className="row justify-content-center">
					<Instructions />
					<div className="col-12">
						<Searching />
					</div>
				</div>
			);
		} else if (this.props.validation.platform) {
			return (
				
				<div className="row justify-content-center">
					<Instructions />
					<div className="col-12">
						<CarrierCard />
					</div>
				</div>
			);
		} else {
			return (
				<div className="row justify-content-center" style={{ marginTop: '1rem' }}>
					<div className="col-12">
						<PlatformCard device={ this.props.visitor.device } />
					</div>
				</div>
			);
		}
	}

}

const mapStateToProps = state => ({
	visitor: state.visitor,
	validation: state.validation
})

export default connect(mapStateToProps, {})(StepContainer);