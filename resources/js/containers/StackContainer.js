import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import PlatformCard from '../components/PlatformCard';
import CarrierCard from '../components/CarrierCard';
import Instructions from '../components/Instructions';

class StackContainer extends Component {
	render() {
		return (
			<div className="row justify-content-center">
				<Instructions />
				<div className="col-12">
					<PlatformCard device={ this.props.visitor.device } />
					<CarrierCard />
				</div>
			</div>
		);
	}
}

const mapStateToProps = state => ({
	visitor: state.visitor
})

export default connect(mapStateToProps, {})(StackContainer);